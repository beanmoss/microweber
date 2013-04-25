


<? $here = dirname(__FILE__);
		
	$here =	pathToURL($here).'/';
	

	$uid =  uniqid() ;
	 
		?>

        <script>mw.require('tools.js');</script>
        <script>mw.require('url.js');</script>
        <script>mw.require('events.js');</script>
        <style type="text/css">
        html,body,#container,#pickfiles_<? print $uid  ?>{
          position: absolute;
          width: 100%;
          height: 100%;
          top: 0;
          left: 0;
          background: transparent;
        }

        </style>
 
        <script type="text/javascript" src="<? print $here ?>js/plupload.js"></script>
        <script type="text/javascript" src="<? print $here ?>js/plupload.html5.js"></script>
        <script type="text/javascript" src="<? print $here ?>js/plupload.html4.js"></script>


 


        <div id="container">
            <div id="pickfiles_<? print $uid  ?>" href="javascript:;">&nbsp;</div>
        </div>


        <script type="text/javascript">
            mw.require('archive.js?v=1');
        </script>
        <script type="text/javascript">
            mw.require('files.js');
        </script>
        <script type="text/javascript">


        receiveMessage = function(event){
               alert(event)
             }


            Name = this.name;

            mwd.body.className +=' ' + Name;

            Params = mw.url.getUrlParams(window.location.href);

           $(document).ready(function(){

             window.addEventListener("message", receiveMessage, true);    

            var multi = Params.multiple === true;


            var filters = [ {title:"", extensions : Params.filters} ]

           this_frame = parent.mw.$("iframe[name='"+Name+"']");





            var uploader = new plupload.Uploader({
                runtimes : 'html5,html4',
                browse_button : 'pickfiles_<? print $uid  ?>',
                debug : 1,
                container: 'container',
				chunk_size : '3mb',
                url : '<? print site_url('plupload'); ?>',
                filters:filters,
                multi_selection:multi
            });


            /*
            mw.on.hashParam("params", function(){
               if(this != undefined){
                var arr = this.split("|"), i=0, l=arr.length;
                __U = mw.url.strip(uploader.settings.url);
                for(; i<l; i++){
                    var xa = arr[i].split(":");
                    __U =  mw.url.set_param(xa[0], xa[1], __U);
                }
                uploader.settings.url = __U;

               }
            })   */











            uploader.init();


            uploader.bind('FilesAdded', function(up, files) {
               this_frame.trigger("FilesAdded", files);
                uploader.start();
                $(mwd.body).addClass("loading");
            });

            uploader.bind('UploadProgress', function(up, file) {
               this_frame.trigger("progress", file);

            });

            uploader.bind('FileUploaded', function(up, file, info){

              var json =  jQuery.parseJSON(info.response);

              if(file.name.contains('zip')){

                  /*
                  mw.files.unzip(json.src, function(){
                    this_frame.trigger("FileUploaded", this);
                  });


               return false;   */
              }

              if(typeof json.error == 'undefined'){
                 this_frame.trigger("FileUploaded", json);
              }
              else{
                this_frame.trigger("responseError", json);
                $(mwd.body).removeClass("loading");
              }

            });

            uploader.bind('UploadComplete', function(up, files){
              this_frame.trigger("done", files);
               $(mwd.body).removeClass("loading");
            });

            uploader.bind('FilesAdded', function(up, files){
              this_frame.trigger("FilesAdded", [files, up.runtime]);
            });

            uploader.bind('Error', function(up, err){
             this_frame.trigger("error", err.file);
             $(mwd.body).removeClass("loading");
	        });


                 $(document.body).click(function(){
                       this_frame.trigger("click");
                 });
            });

        </script>
 