function upload_files(){
     $(".dinhkem").change(function() {
      event.preventDefault();
      $(".progress").show();
      var formData = new FormData($("#dinhkemform")[0]);
       $.ajax({
        url: "post.upload_files.php",
        type: "POST",
        cache: false, contentType: false,
        data: formData, processData:false,
        xhr: function(){
            var xhr = $.ajaxSettings.xhr();
            if(xhr.upload) {
                xhr.upload.addEventListener('progress', function(event) {
                    var percent = 0;
                    var position = event.loaded || event.position;
                    var total = event.total;
                    if (event.lengthComputable) {
                        percent = Math.ceil(position / total * 100);
                    }
                    $(".progress .progress-bar").css("width", + percent +"%");
                    $(".progress .progress-bar").text(percent +"%");
                }, true);
            }
            return xhr;
        },
        success: function(datas) {
            if(datas=='Failed'){
                $.gritter.add({
                    title:"Không thể thêm tập tin",
                    text:"Không thể thêm tập tin",
                    image:"assets/img/login.png",
                    sticky:false,
                    time:""
                });
            } else {
                $("#dinhkem_list").prepend(datas); delete_file();
                $(".progress").fadeOut("slow");
            }
        },
        mimeType:"multipart/form-data"
        }).fail(function() {
            $.gritter.add({
                title:"Không thể Upload tập tin",
                text:"Không thể Upload tập tin",
                image:"assets/img/login.png",
                sticky:false,
                time:""
            });
        });
    });
}

function delete_file(){
    var link_delete; var _this;
    $(".delete_file").click(function(){
        link_delete = $(this).attr("href"); _this = $(this);
        $.ajax({
            url: link_delete,
            type: "GET",
            success: function(datas) {
                _this.parents("div.items").fadeOut("slow", function(){
                    $(this).remove();
                });
            }
        }).fail(function() {
        });
    });
}

function remove_form_file(){
    var _this;
    $(".delete_file").click(function(){
        link_delete = $(this).attr("href"); _this = $(this);
        _this.parents("div.items").fadeOut("slow", function(){
            $(this).remove();
        });
    });
}