/**
 * Created by kamalyu on 14-5-15.
 */
$(function(){
    initAdminPanel();
    if($('#my-dropzone').length > 0){
        Dropzone.autoDiscover = false;
        initDropZone();
    }
//    console.log(typeof global_album_img_arr);
    if(typeof(global_album_img_arr) !== "undefined" && typeof(global_album_time_arr) !== "undefined"){
        addMockFile(global_album_img_arr, global_album_time_arr);
    }
});

function addMockFile(global_album_img_arr, global_album_time_arr){
    $.each(global_album_img_arr,function(i,val){
        // Create the mock file:
//        var mockFile = {
//            name:val.replace(/.*?\//ig,'')
//            ,size:0
//            ,type:"image/jpeg"
//        };
        var mockFile = {
            name:val.name
            ,size:val.size
            ,type:"image/jpeg"
            ,lastTime:global_album_time_arr[i]
        };
        // Call the default addedfile event handler
        window.myDropzone.emit('addedfile',mockFile);
        // And optionally show the thumbnail of the file:
        window.myDropzone.emit('thumbnail',mockFile,val.url);
        
        // If you use the maxFiles option, make sure you adjust it to the        
        // correct amount:
//        var existingFileCount = 1; // The number of files already uploaded
//        myDropzone.options.maxFiles = myDropzone.options.maxFiles - existingFileCount;
    });
}

function initDropZone(){
    window.myDropzone = new Dropzone("#my-dropzone", {
        autoProcessQueue: false,
        dictDefaultMessage: "拖拽或点击上传",
        dictRemoveFile: "删除此图片",
        dictCancelUpload: "取消上传",
        dictCancelUploadConfirmation: "确认停止此次上传？",
        parallelUploads: 50,
        maxFiles: 50,
        maxFilesize: 5,
        thumbnailWidth:240,
        thumbnailHeight:435,
        addRemoveLinks: true,
        uploadMultiple: true,
        previewTemplate: "<div class=\"dz-preview dz-file-preview\" data-time=\"\">\n  <div class=\"dz-details\">\n    <div class=\"dz-filename\"><span data-dz-name></span></div>\n    <div class=\"dz-size\" data-dz-size></div>\n  <div class=\"preview-img-wrapper\"><img data-dz-thumbnail /></div>  \n  </div>\n  <div class=\"dz-progress\"><span class=\"dz-upload\" data-dz-uploadprogress></span></div>\n  <div class=\"dz-success-mark\"><span>✔</span></div>\n  <div class=\"dz-error-mark\"><span>✘</span></div>\n  <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n</div>",
        init: function () {
            this.on("successmultiple", function (files, response) {
                // Gets triggered when the files have successfully been sent.
                // Redirect user or notify of success.
                console.log('all succeed');
                console.log(response);
                window.location.href = 'admin.php';
            });
            this.on("errormultiple", function (files, response) {
                // Gets triggered when there was an error sending the files.
                // Maybe show form again, and notify user of error
                console.log('errormultiple');
                console.log(files);
                console.log(response);
            });
            this.on("addedfile", function (file) {
                //var lastTime;
                if(file.lastModifiedDate) {
                    lastTime = (file.lastModifiedDate).getTime();
                } else {
                    lastTime = file.lastTime;
                }
                if(file.size > 1024 * 500){
                    $(file.previewElement).addClass('size-red');
                }else if(file.size > 1024 * 200){
                    $(file.previewElement).addClass('size-orange');
                }else{
                    $(file.previewElement).removeClass('size-orange size-red');
                }
                console.log(file.lastModifiedDate)
                //lastModfiedDate
                $(file.previewElement).attr("data-time", lastTime);
                // remove duplicate file
                var foundDuplicate = false;
                var nameTmp = {};
                $.each(window.myDropzone.files,function(i,v){
                    if(typeof nameTmp[v.name] != 'undefined'){
                        foundDuplicate = true;
                        return false;
                    }
                    nameTmp[v.name] = 1;
                });
                if(foundDuplicate){
                    // 图片重复，直接删除
                    window.myDropzone.removeFile(file);
                }
                if (!isImage(file)) {
                    // 不是图片，直接删除
                    window.myDropzone.removeFile(file);
                }
            });
        }
    });

    $("#my-dropzone").sortable({
        items:'.dz-preview'
        ,placeholder: "ui-state-highlight"
    }).disableSelection();
    
    $('#create_btn').click(function(e){
        e.preventDefault();
        
        var album_input = $('#album_name');
        var album_name = $.trim(album_input.val());
        if(album_name == ''){
            album_input.addClass('error');
            console.log('未填写名称');
            alert('请填写名称！');
            return false;
        }else{
            album_input.removeClass('error');
        } 
        if(window.myDropzone.files.length == 0){
            console.log('没有添加新图片');
        }
        var album_json = {
            "album_name":album_name
            ,"album_images":[]
            ,"album_time": []

        };
        var thumbImgageSrcArr = [];
        $('.dz-image-preview').each(function(index,elem){
            $previewObj = $(this);
            // 根据顺序收集所有图片名
            album_json.album_images.push($previewObj.find('[data-dz-name]').text().split("?")[0]);
            album_json.album_time.push($previewObj.attr("data-time"));
            // 手机前四个图片 URL 用来组合 DEMO 缩略图
            if(index<4){
                thumbImgageSrcArr.push($previewObj.find('[data-dz-thumbnail]').attr('src'));
            }
        });
        console.log(thumbImgageSrcArr);
        if(thumbImgageSrcArr.length != 0){
            album_json['thumb'] = gen4Thumb(thumbImgageSrcArr);
        }
        
        if(window.myDropzone.files.length == 0 &&
            album_json.album_images.length != 0){
            // 没有上传新图片，并且图片名列表不为空
            // 可能是重新排序，也可能是修改DEMO名
            submitAlbumInfo(album_json);
        }else{
            submitDropzone(album_json);
        }
    })
}

function submitAlbumInfo(album_json){
    $.ajax({
        url:'upload.php'
        ,type:'POST'
        ,data:{"album_json":JSON.stringify(album_json),"dir":global_album_dir}
        ,success:function(data,status,xhr){
            console.log('all succeed');
            console.log(data);
            window.location.href = 'admin.php';
        }
        ,error:function(xhr,status,err){
            console.log(err);
        }
    });
}

function submitDropzone(album_json){
    window.myDropzone.on("sendingmultiple", function(file, xhr, formData) {
        formData.append("album_json", JSON.stringify(album_json)); // Will send the data along with the file as POST data.
        if(typeof(global_album_dir) !== 'undefined' && $.trim(global_album_dir) != ''){
            formData.append("dir",global_album_dir);
        }
    });
    window.myDropzone.processQueue();
}

function initAdminPanel(){
    $('.icon_block').click(function(e){
        e.stopPropagation();
        $('#main_con').removeClass('list_view grid_view')
            .addClass('grid_view');
    });
    $('.icon_list').click(function(e){
        e.stopPropagation();
        $('#main_con').removeClass('list_view grid_view')
            .addClass('list_view');
    });

    $('.album_list li').on('mouseenter',function(e){
        var $li = $(this);
        var qr_area = $li.find('.qr_code');
        if(qr_area.find('img').length == 0){
            var url = $li.data('url');
            var qrcode_url = "http://2.net.co/qr/?qrstr=" + encodeURIComponent(url);

            qr_area.append('<img src="'+qrcode_url+'"/>');
        }
    });
    $('.album_list .title').on('click',function(e){
        if($('#main_con').hasClass('list_view')){
            var $li = $(this).parent();
            if($li.hasClass('expand')){
                $li.removeClass('expand');
            }else{
                $li.siblings().removeClass('expand');
                $li.addClass('expand');
            }            
        }
    });
    
    
    $('#view_all_btn').click(function(e){
        e.preventDefault();
        e.stopPropagation();

        var preview_area = $('#preview_layer');
        if(preview_area.find('img').length == 0){
            var url = $(this).data('url');
            var qrcode_url = "http://2.net.co/qr/?qrstr=" + encodeURIComponent(url);
            var padding_str = '<p>扫描查看所有DEMO列表</p>';
        
            preview_area.find('#preview_panel').append('<img src="'+qrcode_url+'"/>'+padding_str);
        }
        preview_area.fadeIn('fast');
    });
    $('#preview_layer').click(function(e){
        e.preventDefault();
        e.stopPropagation();
        
        $(this).fadeOut('fast');
    });
    
    $('.delAlbum').click(function(e){
        var album_dir = $(this).data('dir');
        var album_name = $(this).data('name');
        if(confirm('确定删除DEMO：'+album_name+'？')){
            //alert("admin.php?del='"+ encodeURIComponent(album_dir) + "'");
            window.location.href='admin.php?del='+encodeURIComponent(album_dir);
        }
    });
    $('.edit_album').click(function(e){
        e.stopPropagation();
    });
}

function isImage(file){
    if (file.type.match(/image.*/)) {
        //is image
        return true;
    }
}

function gen4Thumb(arrSrc){
    if(arrSrc.length<1){
        return '';
    }
    var srcImage1,srcImage2,srcImage3,srcImage4, W, H, drawH;
    
    var tcanvas = document.createElement("canvas");
    tcanvas.width = 500;
    tcanvas.height = 500;
    var tc = tcanvas.getContext("2d");
    //painting the canvas white before painting the image to deal with pngs
    tc.fillStyle = "white";
    tc.fillRect(0, 0, 500, 500);
    //drawing the image on the canvas
    switch(arrSrc.length){
        case 4:
            srcImage1 = new Image();
            srcImage1.src = arrSrc[0];
            W = srcImage1.width;
            tc.drawImage(srcImage1, 0, 0, W, W, 0, 0, 240, 240);

            srcImage2 = new Image();
            srcImage2.src = arrSrc[1];
            W = srcImage2.width;
            tc.drawImage(srcImage2, 0, 0, W, W, 0, 260, 240, 240);

            srcImage3 = new Image();
            srcImage3.src = arrSrc[2];
            W = srcImage3.width;
            tc.drawImage(srcImage3, 0, 0, W, W, 260, 0, 240, 240);

            srcImage4 = new Image();
            srcImage4.src = arrSrc[3];
            W = srcImage4.width;
            tc.drawImage(srcImage4, 0, 0, W, W, 260, 260, 240, 240);
            
            break;
        case 3:
            srcImage1 = new Image();
            srcImage1.src = arrSrc[0];
            W = srcImage1.width;
            tc.drawImage(srcImage1, 0, 0, W, W, 0, 0, 240, 240);

            srcImage2 = new Image();
            srcImage2.src = arrSrc[1];
            W = srcImage2.width;
            tc.drawImage(srcImage2, 0, 0, W, W, 0, 260, 240, 240);

            srcImage3 = new Image();
            srcImage3.src = arrSrc[2];
            W = srcImage3.width;
            tc.drawImage(srcImage3, 0, 0, W, W, 260, 0, 240, 240);
            
            break;
        case 2:
            srcImage1 = new Image();
            srcImage1.src = arrSrc[0];
            W = srcImage1.width;
            H = srcImage1.height;
            drawH = (500 * W)/240;
            if(drawH > H){
                drawH = H;
            }
            tc.drawImage(srcImage1, 0, 0, W, drawH, 0, 0, 240, 500);

            srcImage2 = new Image();
            srcImage2.src = arrSrc[1];
            W = srcImage2.width;
            H = srcImage2.height;
            drawH = (500 * W)/240;
            if(drawH > H){
                drawH = H;
            }
            tc.drawImage(srcImage2, 0, 0, W, drawH, 260, 0, 240, 500);            
            break;
        case 1:
            srcImage1 = new Image();
            srcImage1.src = arrSrc[0];
            W = srcImage1.width;
            tc.drawImage(srcImage1, 0, 0, W, W, 0, 0, 500, 500);
            break;
    }
//    var img = document.getElementById('sprite');
//    img.src = tcanvas.toDataURL("image/png");
    return tcanvas.toDataURL("image/png");
}

jQuery.fn.childOf = function( parentObj ) {
    parentObj = parentObj || [];
    if(parentObj.length == 0) return false;
    
    $this = $(this);
    
    if($this == parentObj){
        return true;
    }
    
    var parents = $this.parents().get();
    for ( j = 0; j < parents.length; j++ ) {
        if ( $(parents[j]).is(parentObj) ) {
            return true;
        }
    }
    return false;
};