/*
This script takes data from a set of html items, and classifies it, allowing the user to navigate using this information as filtering criteria.
this is used in the pilots page, and is what makes the tag-based filtering possible.
*/

'use strict'

//to make a shared variable between global scope and the annonymous function.
// if(!window.categorizer){
//   window.categorizer={};
// }
//annonymous call
document.addEventListener("DOMContentLoaded", function (event) {
    console.log("categorizer.js");
    //get the after-hash data to apply the filter accordingly. (the part of the url that comes after the "#" character.
    function getHash(){
      return decodeURIComponent( window.location.hash )
        .replace(/^\#/,"").split("/")
        .map(function(a){ return (a=="false"?false:a) })
        .map(function(a){ return a.toLowerCase() });
    }
    var urlRequestedSelection=getHash();
    if(!urlRequestedSelection[0]) urlRequestedSelection=false;

    //assign a function to each html element.
    //what item to select as a container of classifiable items.
    var wrapperSelector=".classifiable-container";
    //what item is defined as a visible "item" to be filtered
    var itemSelector=".classifiable-item";
    //what item is defined as the container of the item's classifiable attributes
    var itemDataContainerSelector=".classifiable-attributes";
    //whether masonry plugin is loaded. note: it makes the processing a lot slower.
    var useMasonry=$().masonry!=undefined;

    var updateMasonry=function(){}
    if(useMasonry) updateMasonry=function(){
      $(wrapperSelector).masonry({
        itemSelector: itemSelector+", .categorizer-menu",
        columnWidth: itemSelector+":not(.disappear)",
        // percentPosition: true
      })
    }

    //a visible filtering button element, with it's data
    var ClassifButton=function(myCategorizer,category,tag,$el){
      this.appendTo=function($to){
        return $to.append($el);
      }
      this.removeClass=function(string){
        return $el.removeClass(string);
      }
      this.attr=function(str){
        return $el.attr(str);
      }
      myCategorizer.onAfterFilterAndDisplay(function(filterEvent){
        if(filterEvent.category==category && (filterEvent.tag==tag || tag==false)){
          $el.addClass("active");
        }else{
          $el.removeClass("active");
        }
        //we don't want category buttons to disappear, hence this if.
        if(tag){
          if(filterEvent.category==category){
            $el.addClass("undisappear");
            $el.removeClass("disappear");
          }else{
            $el.addClass("disappear");
            $el.removeClass("undisappear");
          }
        }
      });
      return this;
    }

    //extend the ClassifButton into categories and tag buttons.

    var CategoryButton=function(myCategorizer,category){
      var $el= $(`<a href="#${category}" class="tag" data-category="${category}">${category} </a>`);
      ClassifButton.call(this,myCategorizer,category,false,$el);
      return this;
    }

    var TagButton=function(myCategorizer,category,tag){
      var myList=CategorizedItem.sortedList[category][tag];
      var count = (myList?myList:[]).length;
      var $el= $(`<a href="#${category}/${tag}" class="tag" data-category="${category}" data-item="${tag}">${tag} <span class="count">${count}<span></a>`);
      ClassifButton.call(this,myCategorizer,category,tag,$el);
      return this;
    }

    //representation in the code of a classifiable-items container. It contains reference to all DOM elements and also their data as variables.
    var ClassifiedContainer=function($item){
      var items=this.CategorizedItems=[];
      var $selectionMenu=$('<div class="categorizer-menu"></div>');
      var $catSelectionMenu=$('<div class="category-menu"></div>');
      var $tagSelectionMenu=$('<div class="tag-menu"><p></p></div>');

      var self=this;

      var categoryButtons=[];


      var categorizerAppended=false;

      var _afterFilterAndDisplayCallback=[];
      this.onAfterFilterAndDisplay=function(cb){
        if(typeof cb == 'function'){
          _afterFilterAndDisplayCallback.push(cb);
        }
      }
      this.filterAndDisplay=function(category,tag){
        console.log("filter and display call",category,tag);

        //remove everything
        CategorizedItem.each(function(){
          this.disappear();
        });

        $(wrapperSelector+" .item-categorizer-tag-title").remove();
        $(wrapperSelector+" .item-categorizer-hr").remove();

        function list(filteredSelection){

          //make the stuff we want to appear
          var n=0;
          for(var tag in filteredSelection){
            if(n>0) $(wrapperSelector).append('<hr class="item-categorizer-hr"/>');
            $(wrapperSelector).append('<h2 class="item-categorizer-tag-title">'+tag+'</h2>');
            for(var item of filteredSelection[tag]){
              item.attachNonExclusive();
            }
            n++;
          }

        }
        //each tag has a category, if the user presses one of these categories,
        //the items get sorted according to their tag on that selected category.
        //sort items by their tags in this category of $tagSelectionMenu
        var filteredSelection={};
        if(category && tag){
          console.log("select by tag");
          if(CategorizedItem.sortedList[category]){
            filteredSelection[tag]=CategorizedItem.sortedList[category][tag];
          }else{
            console.warn("could not select by tag, CategorizedItem.sortedList[category] is ",CategorizedItem.sortedList[category]);
            window.location.hash="";
          }
          list(filteredSelection);
        }else if(category){
          console.log("select by category");
          filteredSelection=CategorizedItem.sortedList[category];

          list(filteredSelection);

          /*
          now find those items that don't have a category. It's counter-logic,
          but it helps the content editors to know which items need to be filled.
          */
          filteredSelection={};
          var othersIndex="without "+category;
          filteredSelection[othersIndex]=[];
          CategorizedItem.each(function(){
            if(!this.isAppeared){
              filteredSelection[othersIndex].push(this);
            }
          });
          if(filteredSelection[othersIndex].length){
            list(filteredSelection);
          }

        }else{
          console.log("select nothing");

          $(wrapperSelector+" .item-categorizer-tag-title").remove();
          $(wrapperSelector+" .item-categorizer-hr").remove();
          // filteredSelection=CategorizedItem.sortedList;
          // item.attachNonExclusive();
          CategorizedItem.each(function(){
            this.detach();
            this.reattach();
          });
        }

        self.updateDom();

        for(var cb of _afterFilterAndDisplayCallback){
          cb({category:category,tag:tag});
        }
      }
      this.updateDom=function(){

        if(! categorizerAppended){
          for(var category in CategorizedItem.sortedList){
            new CategoryButton(self,category).appendTo($catSelectionMenu);

            //sort tags alphabetically (order of categories is determined via customizer)
            var ordered = {};
            Object.keys(CategorizedItem.sortedList[category]).sort().forEach(function(key) {
              ordered[key] = CategorizedItem.sortedList[category][key];
            });
            CategorizedItem.sortedList[category]=ordered;

            for(var tag in CategorizedItem.sortedList[category]){
              new TagButton(self,category,tag).appendTo($tagSelectionMenu);

            }
          }
          $item.prepend($selectionMenu);
          $selectionMenu.append($catSelectionMenu);
          $selectionMenu.append($tagSelectionMenu);
          // console.log("appenced");'s
          categorizerAppended=true;
        }
        if(useMasonry) updateMasonry();
      }

      $item.find(itemSelector).each(function(){
        var nci=new CategorizedItem($(this));
      });

      this.updateDom();


      if(urlRequestedSelection){
        this.filterAndDisplay(urlRequestedSelection[0]||false,urlRequestedSelection[1]||false);
      }

      window.onhashchange = function() {
        urlRequestedSelection=getHash();
        self.filterAndDisplay(urlRequestedSelection[0]||false,urlRequestedSelection[1]||false);
      }
    }

    //representation of a classified item that has data and can appear or disappear.
    var CategorizedItem = function ($item) {
        // console.log("classified item",$item);
        if (!CategorizedItem.list) CategorizedItem.list = [];
        if (!CategorizedItem.sortedList) CategorizedItem.sortedList = {};

        CategorizedItem.list.push(this);

        var self = this;
        this.isAppeared=true;
        this.attributes = {};

        var item$=[$item];

        var $reattach=$item.parent();

        $item.find(itemDataContainerSelector).each(function () {
          var $attrList=$(this);

          var name=$attrList.attr("name");

          var thisList = self.attributes[name] = [];
          if(! CategorizedItem.sortedList[name] ) CategorizedItem.sortedList[name]={};

          $attrList.find('li').each(function(){
            var $li=$(this);
            var txt=$li.text();
            if(txt){
              txt = txt.toLowerCase();
              thisList.push( txt );
              if(! CategorizedItem.sortedList[name][txt] ) CategorizedItem.sortedList[name][txt]=[];
              CategorizedItem.sortedList[name][txt].push(self);
            }
          });
        });

        // console.log(CategorizedItem.sortedList);

        this.detach=function(){
          self.isAppeared=false;

          for(var $i of item$){
            $i.detach();
          }
        }

        this.reattach=function(){
          self.isAppeared=true;
          $item.appendTo($reattach);
        }

        this.attachNonExclusive=function($where){
          self.isAppeared=true;
          if(!$where) $where=$reattach;
          //check whether $ is in use (present in DOM)
          for(var $i of item$){
            if(!$i.parent().length){
              $i.appendTo($where);
              return;
            }
          }
          //if all the $el's were in use, create a new one.
          // console.log("attach additional",$item)
          var $ni=$item.clone();
          item$.push($ni);
          $ni.appendTo($where);
        }

        this.matches=function(categoryHas,val){
          if(categoryHas===false){
            return true;
          }else if(self.attributes[categoryHas]){
            if(val===false){
              return true;
            }else if(self.attributes[categoryHas].indexOf(val)!==-1)
              return true
            else
              return false;
          }else{
            //didn't even have matching category
            return false;
          }
        }

        this.appear=function(){
            // $item.fadeIn();
            self.isAppeared=true;
            // $item.css("display","");
            // item$.map(function(a){ a.removeClass("disappear") } );
            item$.map(function(a){ a.appendTo($reattach) } );

        }
        this.disappear=function(){
            // $item.fadeOut();
            self.isAppeared=false;
            // $item.css("display","none");
            // item$.map(function(a){ a.addClass("disappear") } );
            item$.map(function(a){ a.detach() } );

        }
        this.setAppearState=function(state){
          if(state)self.appear();
          else self.disappear();
        }
    }
    CategorizedItem.list = [];
    CategorizedItem.each=function(cb){
        for(var itmno in CategorizedItem.list){
          cb.call(CategorizedItem.list[itmno],CategorizedItem.list[itmno],itmno);
        }
    }
    //apply
    $(wrapperSelector).each(function () {
        new ClassifiedContainer($(this));
    });




});
