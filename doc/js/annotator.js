'use strict';
document.addEventListener("DOMContentLoaded", function (event) {
  console.log("annotator.js");


  window.annotator=new(function(){
    var editable=false;
    var parameters={};

    this.start=function(hashvars={}){
      for(var a in hashvars){
        parameters[a]=hashvars[a];
      }
      if(hashvars.json){
          var xobj = new XMLHttpRequest();
              xobj.overrideMimeType("application/json");
          xobj.open('GET', "res/"+hashvars.json, true);
          xobj.onreadystatechange = function () {
            if (xobj.readyState == 4 && xobj.status == "200") {
              Annotable.applyProps(JSON.parse(xobj.responseText));
            }
          };
          xobj.send(null);
      }
      editable=(hashvars.editable=="true");
      if(editable){
        $('link#annotable').attr("href","annotable-edit.css");
      }
      $("img.annotable").each(function(){
        if(hashvars.img)$(this).attr("src","res/"+hashvars.img);
        new Annotable($(this));
      });
    }
    var Annotable=function($picture){
      var drag={
        start:{},
        annotation:false,
      };
      var $parent=$picture.parent();
      $picture.detach();

      var $el=$('<div class="annotable-container"></div>');
      var $menu=$('<div class="annotable-menu"></div>');

      var $svb=$('<div class="button save menu-item">Save</div>');
      var $ldb=$('<input class="load button menu-item" type="file" id="file" name="file" enctype="multipart/form-data" />');

      $el.css({position:"relative"});
      $el.appendTo($parent);
      $picture.appendTo($el);

      // console.log("EDIT HERE",editable);
      if(editable){
        $menu.appendTo($el);
        $ldb.appendTo($menu);
        $svb.appendTo($menu);

        $ldb.on('mousedown',function(e){
          if(e.button==0){
            e.preventDefault();
            e.stopPropagation();
          }
        });
        $svb.on('mousedown',function(e){
          if(e.button==0){
            e.preventDefault();
            e.stopPropagation();
          }
        });
        $svb.on('mouseup',function(e){
          if(e.button==0){
            Annotation.download();
          }
        });
        $ldb[0].addEventListener('change', readFile, false);
      }

      function readFile (evt) {
        console.log("read");
        var files = evt.target.files;
        var file = files[0];
        var reader = new FileReader();
        reader.onload = function(event) {
          Annotation.applyProps(JSON.parse(event.target.result));
          console.log(event.target.result);
        }

        console.log(reader.readAsText(file));
     }
     if(editable){
        $el.on('mousedown',function(e){
          if(e.button!=0) return;
          e.preventDefault();
          e.stopPropagation();
          console.log(e.originalEvent);
          drag.start.x=e.originalEvent.layerX
          drag.start.y=e.originalEvent.layerY

          let nann=new Annotation({
            left: drag.start.x+"px",
            top: drag.start.y+"px",
            width: 10+"px",
            height: 10+"px",
            'pointer-events':'none',
          });
          // nann.appendTo($el);
          drag.annotation=nann;
        });
        $el.on("mousemove",function(e){
          if(drag.annotation){
            e.stopPropagation();
            // console.log("annot",e);
            drag.annotation.applyProps({
              'position-right': e.originalEvent.layerX,
              'position-bottom': e.originalEvent.layerY
            });
          }
        });
        $el.on('mouseup',function(e){
          if(e.button!=0) return;
          // e.stopPropagation();
          e.preventDefault();
          if(!drag.annotation) return;
          // console.log(e.originalEvent);
          drag.annotation.applyProps({
            'pointer-events': ''
          });
          drag.annotation=false;

        });

      }
      var Annotation=function(props){
        Annotation.list.add(this);
        var self=this;
        var $frame=$('<div class="annotation-frame"></div>');
        var $content=$('<div class="annotation-content"></div>');
        var $rmb=$('<div class="button delete">x</div>');
        var $rszh=$('<div class="annotation-resize">+</div>');
        $content.appendTo($frame);

        if(editable){
          $rmb.appendTo($frame);
          $rszh.appendTo($frame);
          $content.attr('contenteditable','true');
          //$UI
          $frame.draggable();
          $frame.css('position',"absolute");

          $rszh.draggable({
            // start: function() {
            // },
            drag: function() {
              self.applyProps({
                width:$rszh.css("left"),
                height:$rszh.css("top")
              });
            },
            stop: function() {
              $rszh.css({top:"",left:""});
            }
          });
          $rszh.css('position',"absolute");

          $rmb.on('mouseup',function(e){
            if(e.button!=0) return;
            // e.stopPropagation();
            self.remove();
          });
          $frame.on('mousedown',function(e){
            if(e.button!=0) return;
            e.stopPropagation();
          });
        }
        this.applyProps=function(props){
          // console.log("apply",props);
          var css={};
          if(props.left!==undefined)css.left=props.left;
          if(props.top!==undefined)css.top=props.top;
          if(props.width!==undefined)css.width=props.width;
          if(props.height!==undefined)css.height=props.height;
          if(props['position-right']!==undefined)css.width=(props['position-right'] - $frame.offset().left)+"px";
          if(props['position-bottom']!==undefined)css.height=(props['position-bottom'] - $frame.offset().top)+"px";
          if(props['pointer-events']!==undefined)css['pointer-events']=props['pointer-events'];
          $frame.css(css);
          if(props.content!==undefined)$content.html(props.content);
        }
        this.get=function(){
          var ret={}
          var offset=$frame.offset();
          var moffset=$frame.parent().offset();

          ret.left=offset.left-moffset.left;
          ret.top=offset.top-moffset.top;
          ret.width=$frame.css('width');
          ret.height=$frame.css('height');
          ret.content=$content.html();
          return ret;
        }
        if(props){
          this.applyProps(props);
        }
        this.appendTo=function($a){
          $frame.appendTo($a);
        }

        this.remove=function(){
          Annotation.list.delete(self);
          $frame.remove();
        }
        this.appendTo(Annotation.$parent);
      }
      Annotation.list=new Set();
      Annotation.get=function(){
        let data=[];
        Annotation.list.forEach(function(annotation){
          data.push(annotation.get())
        });
        return data;
      }
      Annotation.download=function(){
        var data=Annotation.get();
        console.log(data);
        if(!data) return;
        data=JSON.stringify(data);
        var type="json";
        var filename=parameters.json?parameters.json:"annotation.json";
        var file = new Blob([data], {type: type});
        if (window.navigator.msSaveOrOpenBlob) // IE10+
        window.navigator.msSaveOrOpenBlob(file, filename);
        else { // Others
          var a = document.createElement("a"),
          url = URL.createObjectURL(file);
          a.href = url;
          a.download = filename;
          document.body.appendChild(a);
          a.click();
          setTimeout(function() {
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
          }, 0);
        }
      }

      Annotation.applyProps=function(annotations){
        for(var annotationData of annotations){
          let nann=new Annotation(annotationData);
        }
      }

      Annotation.$parent=$el;

      Annotable.applyProps=Annotation.applyProps;

    }


    return this;
  })();
});
