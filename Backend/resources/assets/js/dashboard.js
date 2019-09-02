$( document ).ready(function() {
    
    $.ajaxPrefilter(function(options, originalOptions, xhr) { // this will run before each request
        var token = $("meta[name='csrf-token']").attr('content'); // or _token, whichever you are using

        if (token) {
            return xhr.setRequestHeader('X-CSRF-TOKEN', token); // adds directly to the XmlHttpRequest Object
        }
    });

    // CounterUp Plugin
    $('.counter').counterUp({
        delay: 10,
        time: 1000
    });

    $('#stm_users_table').DataTable({
        processing: true,
        serverSide:true,
        'sAjaxSource':'api/users',
        "order" : [[3, "desc"]],
        "oLanguage": {
            "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
        }
    });

    $(document).on('change',  "#competitionForm #photo" , function(){
        readURL(this, '#competitionForm #photoPreview');
    });

    var adminusers_table = $('#adminusers_table').DataTable({
                processing: true,
                serverSide:true,
                "sAjaxSource": "api/adminusers",
                "order" : [[3, "desc"]],
                "oLanguage": {
                    "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
                }
    });

    var category_table = $('#category_table').DataTable({
                processing: true,
                serverSide:true,
                "sAjaxSource": "api/categories",
                "oLanguage": {
                    "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
                }
    });

    var newscategory_table = $('#newscategory_table').DataTable({
                processing: true,
                serverSide:true,
                "sAjaxSource": "api/newscategories",
                "oLanguage": {
                    "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
                }
    });

    var news_table = $('#news_table').DataTable({
                processing: true,
                serverSide:true,
                "sAjaxSource": "api/news",
                "order" : [[1, "desc"]],
                "oLanguage": {
                    "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
                }
    });


    var faqs_table = $('#faqs_table').DataTable({
                processing: true,
                serverSide:true,
                "sAjaxSource": "api/faqs",
                "order" : [[2, "desc"]],
                "oLanguage": {
                    "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
                }
    });
    var donation_table = $('#donation_table');

    donation_table.dataTable({
                processing: true,
                serverSide:true,
                "sAjaxSource": "api/donation",
                "order" : [[3, "desc"]],
                "oLanguage": {
                    "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
                },
                fnServerData: function (sSource, aoData, fnCallback)
                {
                    var artist = $("#donation_artist_name").val();
                    var start_date = $("#donation_start_date").val();
                    var end_date = $("#donation_end_date").val();
                    // console.log([artist, start_date, end_date]);
                    Array.prototype.push.apply(aoData, [
                        {"name": "artist", "value": artist},
                        {"name": "start_date", "value": start_date},
                        {"name": "end_date", "value": end_date}
                    ]);
                    $.ajax({
                        dataType: 'json',
                        type: 'GET',
                        url: sSource,
                        data: aoData,
                        success: fnCallback
                        // beforeSend: function(request) {
                        //   return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                        // }
                    });
                }
    });
    
    $('.donation-custom-filter').on('change', function (){
        donation_table.api().ajax.reload();
    });

    var artist_campaigns_table = $('#artist_campaigns_table').DataTable({
                processing: true,
                serverSide:true,
                "oLanguage": {
                "sSearch": "Track Search: "
                },
                "sAjaxSource": "/admin/api/campaigns/"+$('#artistId').val(),
                "order" : [[3, "desc"]],
                "oLanguage": {
                    "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
                }
    });

    // recommended tracks
    var recommended_tracks = $('#recommended_tracks_table').DataTable({
                processing: true,
                serverSide:true,
                "sAjaxSource": "api/recommended_tracks",
                "oLanguage": {
                    "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
                }
    });

    var campaign_descover_table = $('#campaign_descover_table');
    campaign_descover_table.dataTable({
                processing: true,
                serverSide:true,
                "sAjaxSource": "api/discover-tracks",
                "order" : [[4, "desc"]],
                "oLanguage": {
                    "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>",
                    "sSearch": "Track Search: "
                },
                fnServerData: function (sSource, aoData, fnCallback)
                {
                    var artist = $("#campaign_artist_name").val();
                    var start_date = $("#campaign_start_date").val();
                    var end_date = $("#campaign_end_date").val();
                    Array.prototype.push.apply(aoData, [
                        {"name": "artist", "value": artist},
                        {"name": "start_date", "value": start_date},
                        {"name": "end_date", "value": end_date}
                    ]);
                    $.ajax({
                        dataType: 'json',
                        type: 'GET',
                        url: sSource,
                        data: aoData,
                        success: fnCallback
                        // beforeSend: function(request) {
                        //   return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                        // }
                    });
                }
    });
    $('.campaign-custom-filter').on('change', function (){
        campaign_descover_table.api().ajax.reload();
    });
    
    var campaign_remix_table = $('#campaign_remix_table');
    campaign_remix_table.dataTable({
                processing: true,
                serverSide:true,
                "sAjaxSource": "api/remix-tracks",
                "order" : [[4, "desc"]],
                "oLanguage": {
                    "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>",
                    "sSearch": "Track Search: "
                },
                fnServerData: function (sSource, aoData, fnCallback)
                {
                    var artist = $("#remix_artist_name").val();
                    var start_date = $("#remix_start_date").val();
                    var end_date = $("#remix_end_date").val();
                    Array.prototype.push.apply(aoData, [
                        {"name": "artist", "value": artist},
                        {"name": "start_date", "value": start_date},
                        {"name": "end_date", "value": end_date}
                    ]);
                    $.ajax({
                        dataType: 'json',
                        type: 'GET',
                        url: sSource,
                        data: aoData,
                        success: fnCallback
                        // beforeSend: function(request) {
                        //   return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                        // }
                    });
                }
    });

    $('.remix-custom-filter').on('change', function (){
        campaign_remix_table.api().ajax.reload();
    });

    $(document).on('click', '.discover-status', function() {
        var urlLink = this.id;
        var currentElement = $(this);

        $.ajax({
            url: this.id,
            type: 'get',
            success: function(data) {
                if (data["status"] == "added") {
                    currentElement.find("i").removeClass('fa-times');
                    currentElement.find("i").addClass('fa-check');

                    currentElement.attr("title", "Removed From Discover");
                    currentElement.attr("id", urlLink.substr(0, urlLink.indexOf("/add/")) + "/remove/" + data["data"]);
                } else {
                    currentElement.find("i").removeClass('fa-check');
                    currentElement.find("i").addClass('fa-times');

                    currentElement.attr("title", "Add To Discover");
                    currentElement.attr("id", urlLink.substr(0, urlLink.indexOf("/remove/")) + "/add/" + data["data"]);
                }
            },
            error: function() {}
        });
    });

    $(document).on("click", "[data-method]", function(e)
    {   
        if (confirm("Are you sure, you want to delete record?") == true) {
                $.ajax({
                url: this.id,
                type: 'post',
                data: {_method: 'DELETE' },
                success: function(data, textStatus, jqXHR) {
                    window.location.href = location.href;
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
            }); 
        }
        else
        {
            return false;
        }
    });

    $(document).on("click", "a.StmUserEdit", function(event){
        $.ajax({
                url: this.id,
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $('#UserEditModal').html(data).modal();
                },
                complete: function() { // fires after the ajax is fully complete
                    $("form#stmUserForm").validate({
                    rules: {
                                name: {required : true},
                                phone:{required:true,digits:true},
                                email:{required:true, email:true,
                                remote: {
                                url: base_url+"/admin/users/uniqueEmail",
                                data:{  user_id: function() 
                                        {
                                            return $("#user_id").val();
                                        }
                                    },
                                type: "post",
                                beforeSend: function(request) {
                                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                                }
                            }
                        }
                            },
                        messages:{
                                name: {required:"Please enter name"},
                                email: {required:"Please enter email",remote:"Email already exists"},
                                phone: {required:"Please enter phone"}
                            },
                            submitHandler: function (form) {
                                this.submitButton.disabled = true;
                                $("body").addClass('loading');
                                form.submit();
                            }
                });
            }
            }); 
    });
    
    var artists_table = $('#artists_table').DataTable({
                        processing: true,
                        serverSide:true,
                        "sAjaxSource": "api/artists",
                        "order" : [[3, "desc"]],
                        "fnServerData": function( sSource, aoData, fnCallback )
                        {
                            var subscription = $('select[name="subscription"]').val();
                            aoData.push({ "name": "subscription", "value": subscription });
                            $.ajax({
                                    dataType: 'json',
                                    type: 'POST',
                                    url: sSource,
                                    data: aoData,
                                    xhrFields: { withCredentials: true },
                                    success: fnCallback,
                                    beforeSend: function(request) {
                                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                                    }
                                });
                        },
                        "oLanguage": {
                            "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
                        }
                    });
        
        $(document).on('change',"select[name='subscription']" ,function( event ) {
            artists_table.draw();
        });

        $(document).on("click", "a.ArtistEdit", function(event){
        $.ajax({
                url: this.id,
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $('#ArtistEditModal').html(data).modal();
                },
                complete: function() { // fires after the ajax is fully complete
                    $("form#artistUserForm").validate({
                    rules: {
                                name: {required : true},
                                phone:{required:true, digits:true},
                                email:{required:true, email:true,
                                remote: {
                                url: base_url+"/admin/users/uniqueEmail",
                                data:{  user_id: function() 
                                        {
                                            return $("#user_id").val();
                                        }
                                    },
                                type: "post",
                                beforeSend: function(request) {
                                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                                }
                            }},
                                subscription:{required:true}
                            },
                        messages:{
                                name: {required:"Please enter name"},
                                email: {required:"Please enter email",remote:"Email already exists"},
                                phone: {required:"Please enter phone"},
                                subscription: {required:"Please select subscription"}
                            },
                            submitHandler: function (form) {
                                this.submitButton.disabled = true;
                                $("body").addClass('loading');
                                form.submit();
                            }
                    });
                }
            }); 
        });

        $(document).on("click", "a.AdminUserEdit", function(event){
        $.ajax({
                url: this.id,
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $('#adminUserEditModal').html(data).modal();
                },
                complete: function() { // fires after the ajax is fully complete
                    adminuser_validate();
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
            });
        });

        $(document).on("click", "#addadminuser", function(event){
        $.ajax({
                url: base_url+'/admin/adminusers/create',
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $('#adminUserEditModal').html(data).modal();
                },
                complete:function() {
                    adminuser_validate();
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
            }); 
        });

        function adminuser_validate(){
                $("form#adminUserForm").validate({
                    rules: {
                                name: {required : true},
                                soundcloud_url : {url: true},
                                facebook_url : {url: true},
                                twitter_url : {url: true},
                                youtube_url : {url: true},
                                instagram_url : {url: true},
                                password_confirmation: {
                                    equalTo : "#password"
                                },
                                email:{required:true, email:true,
                                remote: {
                                url: base_url+"/admin/users/uniqueEmail",
                                data:{  user_id: function() 
                                        {
                                            return $("#user_id").val();
                                        }
                                    },
                                type: "post",
                                beforeSend: function(request) {
                                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                                }
                            }
                        }
                            },
                        messages:{
                                name: {required:"Please enter name"},
                                email: {required:"Please enter email",remote:"Email already exists"},
                                password_confirmation: {equalTo:"Password does not match"},
                                soundcloud_url : {url: "Please enter a valid URL.(eg. http://stm.com)"},
                                facebook_url : {url: "Please enter a valid URL.(eg. http://stm.com)"},
                                twitter_url : {url: "Please enter a valid URL.(eg. http://stm.com)"},
                                youtube_url : {url: "Please enter a valid URL.(eg. http://stm.com)"},
                                instagram_url : {url: "Please enter a valid URL.(eg. http://stm.com)"},
                            },
                            submitHandler: function (form) {
                                this.submitButton.disabled = true;
                                $("body").addClass('loading');
                                form.submit();
                            }
                });
        }

        $(document).on("click", "#addartist", function(event){
        $.ajax({
                url: base_url+'/admin/artists/create',
                type: 'get',

                success: function(data, textStatus, jqXHR) {
                    $('#ArtistEditModal').html(data).modal();
                },
                complete:function() {
                    $("form#artistUserForm").validate({
                    rules: {
                                name: {required : true},
                                email:{required:true, email:true,
                                remote: {
                                url: base_url+"/admin/users/uniqueEmail",
                                data:{  user_id: function() 
                                        {
                                            return $("#user_id").val();
                                        }
                                    },
                                type: "post",
                                beforeSend: function(request) {
                                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                                }
                            }},
                                subscription:{required:true}
                            },
                        messages:{
                                name: {required:"Please enter name"},
                                email: {required:"Please enter email",remote:"Email already exists"},
                                phone: {required:"Please enter phone"},
                                subscription: {required:"Please select subscription"}
                            }
                    });
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
            }); 
        });


        $(document).on("click", "a.categoryEdit", function(event){
        $.ajax({
                url: this.id,
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $('#categoryModal').html(data).modal();
                },
                complete: function() { // fires after the ajax is fully complete
                    category_validate();
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
            }); 
        });

        function category_validate()
        {
                $("form#categoryForm").validate({
                    rules: {
                                name: {required : true},
                                description:{required:true},
                            },
                        messages:{
                                name: {required:"Please enter category name"},
                                description: {required:"Please enter description"},
                            },
                            submitHandler: function (form) {
                                this.submitButton.disabled = true;
                                $("body").addClass('loading');
                                form.submit();
                            }
                });
        }

        $(document).on("click", "#addcategory", function(event){
        $.ajax({
                url: base_url+'/admin/categories/create',
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $('#categoryModal').html(data).modal();
                },
                complete: function() { // fires after the ajax is fully complete
                    category_validate();
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
            })
        });

        $(document).on("click", "a.newscategoryEdit", function(event){
        $.ajax({
                url: this.id,
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $('#newscategoryModal').html(data).modal();
                },
                complete: function() { // fires after the ajax is fully complete
                    newscategory_validate();
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
            }); 
        });

        function newscategory_validate()
        {
                $("form#newscategoryForm").validate({
                    rules: {
                                name: {required : true},
                                description:{required:true},
                            },
                        messages:{
                                name: {required:"Please enter news category name"},
                                description: {required:"Please enter description"},
                            },
                            submitHandler: function (form) {
                                this.submitButton.disabled = true;
                                $("body").addClass('loading');
                                form.submit();
                            }
                });
        }

        $(document).on("click", "#addnewscategory", function(event){
        $.ajax({
                url: base_url+'/admin/newscategories/create',
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $('#newscategoryModal').html(data).modal();
                },
                complete: function() { // fires after the ajax is fully complete
                    newscategory_validate();
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
            })
        });

        
        // news
        $(document).on("click", "#addnews", function(event){
        $.ajax({
                url: base_url+'/admin/news/create',
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $('#newsModal').html(data).modal();
                    
                                     
                },
                complete: function() { // fires after the ajax is fully complete
                    news_validate();
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
            }); 
        });

        $(document).on("click", "a.newsEdit", function(event){
        $.ajax({
                url: this.id,
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $('#newsModal').html(data).modal();                    
                },
                complete: function() { // fires after the ajax is fully complete
                    news_validate();
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
            }); 
        });

        function news_validate()
        {
                $("form#newsForm").validate({
                    rules: {
                                title: {required : true},
                                description:{required:true},
                            },
                        messages:{
                                title: {required:"Please enter name"},
                                description: {required:"Please enter description"},
                            },
                            submitHandler: function (form) {
                                this.submitButton.disabled = true;
                                $("body").addClass('loading');
                                form.submit();
                            }
                });
        }

        // faqs
        $(document).on("click", "#addfaqs", function(event){
        $.ajax({
                url: base_url+'/admin/faqs/create',
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $('#faqsModal').html(data).modal();                
                },
                complete: function() { // fires after the ajax is fully complete
                    faqs_validate();
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
            }); 
        });

        $(document).on("click", "a.faqsEdit", function(event){
        $.ajax({
                url: this.id,
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $('#faqsModal').html(data).modal();                    
                },
                complete: function() { // fires after the ajax is fully complete
                    faqs_validate();
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
            }); 
        });

        function faqs_validate()
        {
                $("form#faqsForm").validate({
                    rules: {
                                title: {required : true},
                                comment:{required:true},
                            },
                        messages:{
                                title: {required:"Please enter title"},
                                comment: {required:"Please enter comment"},
                            }
                });
        }

    var members_table = $('#members_table').DataTable({
        processing: true,
        serverSide:true,
        "bLengthChange": false,
        "bInfo" : false,
        'sAjaxSource':'api/memberstracks',
        "fnInitComplete": function(oSettings) {
            $('.dataTable thead').hide();
        },
        "fnDrawCallback": function() {
            var pageCount = Math.ceil((this.fnSettings().fnRecordsDisplay()) / this.fnSettings()._iDisplayLength);
             
            if (pageCount > 1)  {
                 $('#members_table_paginate.dataTables_paginate').show();
            } else {
                 $('#members_table_paginate.dataTables_paginate').hide();
            }
            var tableclass = $('#members').attr('class');
            
            if(tableclass == 'tab-pane fade p-v-lg active in')
            {
                load_audio_player();
            }
        },
        "rowCallback": function( row, data, index ) {
            var tableclass = $('#members').attr('class');
            
            if(tableclass == 'tab-pane fade p-v-lg active in')
            {
                $('.allTracks').append('<a href="'+$('td div.search-item .playAudio', row).attr('href')+'"></a>');
            }
        },
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": "",
            "sEmptyTable": "Tracks not available",
            "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
        }
    });

function load_audio_player()
{
    setTimeout(function() {
        var tracksArray = [];
        $('.allTracks').find('a').each(function(e) {
            tracksArray.push($(this).attr('href'));
        });
        
        ToneDen.player.create({
            // debug: true,
            dom: "#player",
            skin: 'light',
            urls: tracksArray,
            soundcloudConsumerKey: '77efa4da8503db6d1ebecc1448c9107f',
            mini: true,
        });
        // alert(tracksArray);
        // ToneDen.player.getInstanceByDom("#player").togglePause();
        // ToneDen.player.getInstanceByDom("#player").play();
        ToneDen.player.global.playTrack(tracksArray[0]);
    }, 2000);
}

    var unregistered_table = $('#unregistered_table').DataTable({
        processing: true,
        serverSide:true,
        "bLengthChange": false,
        "bInfo" : false,
        'sAjaxSource':'api/unregisteredtracks',
        "fnInitComplete": function(oSettings) {
            $('.dataTable thead').hide();
        },
        "fnDrawCallback": function() {
            var pageCount = Math.ceil((this.fnSettings().fnRecordsDisplay()) / this.fnSettings()._iDisplayLength);
             
            if (pageCount > 1)  {
                 $('#unregistered_table_paginate.dataTables_paginate').show();
            } else {
                 $('#unregistered_table_paginate.dataTables_paginate').hide();
            }
            load_audio_player();
        },
        "rowCallback": function( row, data, index ) {
            var tableclass = $('#all').attr('class');
            
            if(tableclass == 'tab-pane fade in p-v-lg active' || tableclass == 'tab-pane fade p-v-lg active in')
            {
                $('.allTracks').append('<a href="'+$('td div.search-item .playAudio', row).attr('href')+'"></a>');
            }
        },
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": "",
            "sEmptyTable": "Tracks not available",
            "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
        }
    });

    var pro_users_table = $('#pro_users_table').DataTable({
        processing: true,
        serverSide:true,
        "bLengthChange": false,
        "bInfo" : false,
        'sAjaxSource':'api/prouserstracks',
        "fnInitComplete": function(oSettings) {
            $('.dataTable thead').hide();
        },
        "fnDrawCallback": function() {
            var pageCount = Math.ceil((this.fnSettings().fnRecordsDisplay()) / this.fnSettings()._iDisplayLength);
             
            if (pageCount > 1)  {
                 $('#pro_users_table_paginate.dataTables_paginate').show();
            } else {
                 $('#pro_users_table_paginate.dataTables_paginate').hide();
            }

            var tableclass = $('#pro_users').attr('class');
            
            if(tableclass == 'tab-pane fade p-v-lg active in')
            {
                load_audio_player();
            }
        },
        "rowCallback": function( row, data, index ) {
            var tableclass = $('#pro_users').attr('class');
            
            if(tableclass == 'tab-pane fade p-v-lg active in')
            {
                $('.allTracks').append('<a href="'+$('td div.search-item .playAudio', row).attr('href')+'"></a>');
            }
        },
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": "",
            "sEmptyTable": "Tracks not available",
            "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
        }
    });

    var bin_tracks_table = $('#bin_tracks_table').DataTable({
        processing: true,
        serverSide:true,
        "bLengthChange": false,
        "bInfo" : false,
        'sAjaxSource':'api/bintracks',
        "fnInitComplete": function(oSettings) {
            $('.dataTable thead').hide();
        },
        "fnDrawCallback": function() {
            var pageCount = Math.ceil((this.fnSettings().fnRecordsDisplay()) / this.fnSettings()._iDisplayLength);
             
            if (pageCount > 1)  {
                 $('#bin_tracks_table_paginate.dataTables_paginate').show();
            } else {
                 $('#bin_tracks_table_paginate.dataTables_paginate').hide();
            }

            var tableclass = $('#bin_users').attr('class');
            
            if(tableclass == 'tab-pane fade p-v-lg active in')
            {
                load_audio_player();
            }
        },
        "rowCallback": function( row, data, index ) {
            var tableclass = $('#bin_users').attr('class');
            
            if(tableclass == 'tab-pane fade p-v-lg active in')
            {
                $('.allTracks').append('<a href="'+$('td div.search-item .playAudio', row).attr('href')+'"></a>');
            }
        },
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": "",
            "sEmptyTable": "Tracks not available",
            "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
        }
    });

    $('#track_search_button').click(function(){
        var searchvalue = $("#track_search").val();
        search_tracks(searchvalue);
    });

    $( "#track_search" ).keydown(function( event ) {
      if ( event.which == 13 ) {
        search_tracks(this.value);
        event.preventDefault();
      }
    });

    function search_tracks(searchvalue)
    {
        // if(searchvalue)
        // {
            var visible = $('.tab-pane.fade.in.p-v-lg.active').attr('id');
            
            if(visible == 'all')
            {
                unregistered_table.search( searchvalue );
                unregistered_table.draw(); 
            }

            if(visible == 'members')
            {
                members_table.search( searchvalue );
                members_table.draw(); 
            }

            if(visible == 'pro_users')
            {
                pro_users_table.search( searchvalue );
                pro_users_table.draw(); 
            }

            if(visible == 'fav_users')
            {
                fav_tracks_table.search( searchvalue );
                fav_tracks_table.draw();
            }

            if(visible == 'bin_users')
            {
                bin_tracks_table.search( searchvalue );
                bin_tracks_table.draw(); 
            }
        // }
        // else
        // {
        //     alert('Enter value');
        // }
    }

    var fav_tracks_table = $('#fav_tracks_table').DataTable({
        processing: true,
        serverSide:true,
        "bLengthChange": false,
        "bInfo" : false,
        'sAjaxSource':'api/favouritetracks',
        "fnInitComplete": function(oSettings) {
            $('.dataTable thead').hide();
        },
        "fnDrawCallback": function() {
            var pageCount = Math.ceil((this.fnSettings().fnRecordsDisplay()) / this.fnSettings()._iDisplayLength);
             
            if (pageCount > 1)  {
                 $('#fav_tracks_table_paginate.dataTables_paginate').show();
            } else {
                 $('#fav_tracks_table_paginate.dataTables_paginate').hide();
            }

            var tableclass = $('#fav_users').attr('class');
            
            if(tableclass == 'tab-pane fade p-v-lg active in')
            {
                load_audio_player();
            }
        },
        "rowCallback": function( row, data, index ) {
            var tableclass = $('#fav_users').attr('class');
            
            if(tableclass == 'tab-pane fade p-v-lg active in')
            {
                $('.allTracks').append('<a href="'+$('td div.search-item .playAudio', row).attr('href')+'"></a>');
            }
        },
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": "",
            "sEmptyTable": "No favourite tracks available",
            "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
        },
    });

    $('#members_table_filter.dataTables_filter').hide();
    $('#unregistered_table_filter.dataTables_filter').hide();
    $('#pro_users_table_filter.dataTables_filter').hide();
    $('#bin_tracks_table_filter.dataTables_filter').hide();
    $('#fav_tracks_table_filter.dataTables_filter').hide();

    $('#members_table.dataTable.no-footer').css('border-bottom', 'none');
    $('#unregistered_table.dataTable.no-footer').css('border-bottom', 'none');
    $('#pro_users_table.dataTable.no-footer').css('border-bottom', 'none');
    $('#bin_tracks_table.dataTable.no-footer').css('border-bottom', 'none');
    $('#fav_tracks_table.dataTable.no-footer').css('border-bottom', 'none');
    

    $(document).on("click", ".delete_track", function(e)
    {
        if (confirm("Are you sure, you want to delete track?") == true) {
                $.ajax({
                url: base_url+'/admin/queue/'+this.id,
                type: 'post',
                data: {_method: 'DELETE' },
                success: function(data, textStatus, jqXHR) {
                    var msg = '<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Track deleted successfully.</div>';
                    $("#successMessage").html(msg);
                    $("#successMessage").show();
                    reload_datatable('delete');

                    setTimeout(function() {
                        $("#successMessage").hide();
                    }, 3000);
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
            }); 
        }
        else
        {
            return false;
        }
    });

    $(document).on("click", ".like_track", function(e)
    {
        var status = $(this).attr('data-id');
        var approval = $(this).attr('data-item');

        if (confirm("Are you sure, you want to "+approval+" track?") == true) {
                $.ajax({
                url: 'track/like',
                type: 'post',
                data:{'track_id':this.id,'status':status},
                success: function(data, textStatus, jqXHR) {
                    var flag = 'approved';
                    if(data == 0)
                    {
                        flag = 'unapproved';
                    }

                    var msg = '<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Track '+flag+' successfully.</div>';
                    $("#successMessage").html(msg);
                    $("#successMessage").show();
                    reload_datatable('like');

                    setTimeout(function() {
                        $("#successMessage").hide();
                    }, 3000);
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
            }); 
        }
        else
        {
            return false;
        }
    });

    $(document).on("click", ".favourite_track", function(e)
    {
        if (confirm("Are you sure, you want to favourite track?") == true) {
                $.ajax({
                url: 'track/favourite',
                type: 'post',
                data:{'track_id':this.id},
                success: function(data, textStatus, jqXHR) {
                    var msg = '<div class="alert alert-success"><button class="close" data-dismiss="alert"></button>Track added in favourites successfully.</div>';
                    $("#successMessage").html(msg);
                    $("#successMessage").show();
                    reload_datatable('fav');

                    setTimeout(function() {
                        $("#successMessage").hide();
                    }, 3000);
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
            }); 
        }
        else
        {
            return false;
        }
    });

    function reload_datatable(state)
    {
        var visible = $('.tab-pane.fade.in.p-v-lg.active').attr('id');

        if(visible == 'all')
        {
            $('#unregistered_table').DataTable().ajax.reload(null, false);
        }

        if(visible == 'members')
        {
            $('#members_table').DataTable().ajax.reload(null, false);
        }

        if(visible == 'pro_users')
        {
            $('#pro_users_table').DataTable().ajax.reload(null, false);
        }

        if(visible == 'fav_users')
        {
            $('#fav_tracks_table').DataTable().ajax.reload(null, false);
        }

        if(visible == 'bin_users')
        {
            $('#bin_tracks_table').DataTable().ajax.reload(null, false);
        }

        if(state == 'delete')
        {
            $('#bin_tracks_table').DataTable().ajax.reload(null, false);
        }

        if(state == 'fav')
        {
            $('#fav_tracks_table').DataTable().ajax.reload(null, false);
        }

        setTimeout(function() {
                $.ajax({
                    url: 'queue_counts',
                    success: function(data, textStatus, jqXHR) {
                       var json = $.parseJSON(data);
                        if (json)
                        {
                            $("#unregistered_count").html(json.unregistered);
                            $("#members_count").html(json.members);
                            $("#prousers_count").html(json.prousers);
                            $("#favourites_count").html(json.favourites);
                            $("#deleted_count").html(json.deletedTracks);
                        }
                    },
                    beforeSend: function(request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    }
                }); 
        }, 2000);

    }

    var payments_table = $('#payments_table');
    payments_table.dataTable({
        processing: true,
        serverSide:true,
        'sAjaxSource':'api/payments',
        "order" : [[3, "desc"]],
        "oLanguage": {
            "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
        },
        "fnServerData": function( sSource, aoData, fnCallback )
        {
            var start_date = $('input[name="start_date"]').val();
            var end_date = $('input[name="end_date"]').val();
            aoData.push({"name": "start_date", "value": start_date},
                        {"name": "end_date", "value": end_date});
            $.ajax({
                    dataType: 'json',
                    type: 'POST',
                    url: sSource,
                    data: aoData,
                    xhrFields: { withCredentials: true },
                    success: fnCallback,
                    beforeSend: function(request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    }
                });
        }
    });

    $('.custom-filter').on('change', function (){
        payments_table.api().ajax.reload();
    });

    // $(document).on('click',"#search_transactions" ,function( event ) {
    //     payments_table.draw();
    // });

    // set default dates
    var start = new Date();
    // set end date to max one year period:
    var end = new Date(new Date().setYear(start.getFullYear()+5));
    
    


    $('.filter_start_date').datepicker({
        orientation: "top auto",
        format: 'dd-mm-yyyy',
        autoclose: true,
        endDate   : end
    // update "toDate" defaults whenever "fromDate" changes
    }).on('changeDate', function(){
        var setDate = formatDate($(this).val());
        setDate = setDate.replace(/-/gi, "/");
        // set the "toDate" start to not be later than "fromDate" ends:
        $('.filter_end_date').datepicker('setStartDate', new Date(setDate));
    });

    $('.filter_end_date').datepicker({
        orientation: "top auto",
        format: 'dd-mm-yyyy',
        autoclose: true,
        endDate   : end
    // update "toDate" defaults whenever "fromDate" changes
    }).on('changeDate', function(){
        var setDate = formatDate($(this).val());
        setDate = setDate.replace(/-/gi, "/");
        // set the "toDate" start to not be later than "fromDate" ends:
        $('.filter_start_date').datepicker('setEndDate', new Date(setDate));
    });

    $('.comp_start_date').datepicker({
        orientation: "top auto",
        format: 'dd-mm-yyyy',
        autoclose: true,
        startDate : start,
        endDate   : end
    });

    
    $('.comp_end_date').datepicker({
        orientation: "top auto",
        format: 'dd-mm-yyyy',
        autoclose: true,
     startDate : start,
        endDate   : end
    // update "fromDate" defaults whenever "toDate" changes
    }).on('changeDate', function(){
        var setDate = formatDate($(this).val());
        var startDate = formatDate($('.comp_start_date').val());
        $('.comp_start_date').datepicker('setEndDate', new Date(setDate));

        if(new Date(startDate) > new Date(setDate)){
            $('.comp_start_date').datepicker('update', null);
        }
    });



    $('.start_date').datepicker({
        orientation: "top auto",
        format: 'dd-mm-yyyy',
        autoclose: true,
        startDate : start,
        endDate   : end
    // update "toDate" defaults whenever "fromDate" changes
    }).on('changeDate', function(){
        var setDate = formatDate($(this).val());
        var endDate = formatDate($('.end_date').val());
       // var announcementDate = formatDate($('.announcement_date').val());
        // set the "toDate" start to not be later than "fromDate" ends:
        $('.end_date').datepicker('setStartDate', new Date(setDate));
        //$('.announcement_date').datepicker('setStartDate', new Date(setDate));

        // if (setDate > endDate) {
        //     $('.end_date').datepicker('update', null);
        //     $('.start_date').datepicker('setEndDate', null);
        // }

        if(new Date(setDate) > new Date(endDate)){
            $('.end_date').datepicker('update', null);
            $('.start_date').datepicker('setEndDate', null);
        }
        // if (setDate > announcementDate) {
        //     $('.announcement_date').datepicker('update', null);
        //     // $('.start_date').datepicker('setEndDate', null);
        // }
    }); 

    $('.end_date').datepicker({
        orientation: "top auto",
        format: 'dd-mm-yyyy',
        autoclose: true,
        startDate : start,
        endDate   : end
    // update "fromDate" defaults whenever "toDate" changes
    }).on('changeDate', function(){
        var setDate = formatDate($(this).val());
        var startDate = formatDate($('.start_date').val());
       // var announcementDate = formatDate($('.announcement_date').val());
        // set the "fromDate" end to not be later than "toDate" starts:
        $('.start_date').datepicker('setEndDate', new Date(setDate));
      //  $('.announcement_date').datepicker('setStartDate', new Date(setDate));

        if(new Date(startDate) > new Date(setDate)){
            $('.start_date').datepicker('update', null);
        }
        // if (startDate > setDate) {
        //     $('.start_date').datepicker('update', null);
        // }

        // if (announcementDate < setDate) {
        //     $('.announcement_date').datepicker('update', null);
        // }
    });

    // $('.announcement_date').datepicker({
    //     orientation: "top auto",
    //     format: 'dd-mm-yyyy',
    //     autoclose: true,
    //     startDate : start,
    //     endDate   : end
    // // update "fromDate" defaults whenever "toDate" changes
    // }).on('changeDate', function(){
    //     var setDate = formatDate($(this).val());
    //     var endDate = formatDate($('.end_date').val());
    //     var startDate = formatDate($('.start_date').val());
    //     // set the "fromDate" end to not be later than "toDate" starts:
    //     $('.start_date').datepicker('setEndDate', new Date(setDate));

    //     if (setDate < startDate) {
    //         $('.start_date').datepicker('update', null);
    //     }
    //     if (setDate < endDate) {
    //         $('.end_date').datepicker('update', null);
    //     }
    // });

    function formatDate(date){
        var d = date.split('-');
        var date = d[1]+'-'+d[0]+'-'+d[2];
        return date;
    }

    var todaysubscribers = $('#todaysubscribers').DataTable({
        processing: true,
        serverSide:true,
        'sAjaxSource':'api/subscribers',
        "oLanguage": {
            "sProcessing": "<img class='img_loader' src="+base_url+"/images/reload.gif>"
        }
    });

    $(document).on("click", "a.profileEdit", function(event){
        $.ajax({
                url: this.id,
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $('#profileEditModal').html(data).modal();
                },
                complete: function() { // fires after the ajax is fully complete
                    $("form#profileForm").validate({
                    rules: {
                                name: {required : true},
                                password_confirmation : {equalTo : "#password"},
                                phone:{required:true, digits:true},
                                soundcloud_url : {url: true},
                                facebook_url : {url: true},
                                twitter_url : {url: true},
                                youtube_url : {url: true},
                                instagram_url : {url: true},
                                email:{required:true, email:true,
                                remote: {
                                url: base_url+"/admin/users/uniqueEmail",
                                data:{  user_id: function() 
                                        {
                                            return $("#user_id").val();
                                        }
                                    },
                                type: "post",
                                beforeSend: function(request) {
                                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                                    }
                                }
                            }
                            },
                        messages:{
                                name: {required:"Please enter name"},
                                email: {required:"Please enter email",remote:"Email already exists"},
                                phone: {required:"Please enter phone"},
                                soundcloud_url : {url: "Please enter a valid URL.(eg. http://stm.com)"},
                                facebook_url : {url: "Please enter a valid URL.(eg. http://stm.com)"},
                                twitter_url : {url: "Please enter a valid URL.(eg. http://stm.com)"},
                                youtube_url : {url: "Please enter a valid URL.(eg. http://stm.com)"},
                                instagram_url : {url: "Please enter a valid URL.(eg. http://stm.com)"},
                            },
                            submitHandler: function (form) {
                                this.submitButton.disabled = true;
                                $("body").addClass('loading');
                                form.submit();
                            }
                });
            }
            }); 
    });

    $(document).on("click", "a.sendMailToArtist", function(event){
        $.ajax({
                url: this.id,
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $('#ArtistEditModal').html(data).modal();
                },
                complete: function() { // fires after the ajax is fully complete
                    $("form#sendMailForm").validate({
                    rules: {
                                subject: {required : true},
                                message:{required:true}
                        },
                        messages:{
                                subject: {required:"Please enter subject"},
                                message: {required:"Please enter message"}
                        },
                        submitHandler: function (form) {
                            this.submitButton.disabled = true;
                            $("body").addClass('loading');
                            form.submit();
                        }
                });
            }
        });
    });

    $(document).on("click", "a.sendMailToUser", function(event){
        $.ajax({
                url: this.id,
                type: 'get',
                success: function(data, textStatus, jqXHR) {
                    $('#UserEditModal').html(data).modal();
                },
                complete: function() { // fires after the ajax is fully complete
                    $("form#sendMailForm").validate({
                    rules: {
                                subject: {required : true},
                                message:{required:true}
                        },
                        messages:{
                                subject: {required:"Please enter subject"},
                                message: {required:"Please enter message"}
                        },
                        submitHandler: function (form) {
                            this.submitButton.disabled = true;
                            $("body").addClass('loading');
                            form.submit();
                        }
                });
            }
        });
    });

    $('.tab_click').click(function(){
        var tabid = this.id;
        if(tabid == 'unregister_tab')
        {
            $('.allTracks').html('');
            unregistered_table.draw();
        }
        else if(tabid == 'members_tab')
        {
            $('.allTracks').html('');
            members_table.draw();
        }
        else if(tabid == 'prousers_tab')
        {
            $('.allTracks').html('');
            pro_users_table.draw();
        }
        else if(tabid == 'favtracks_tab')
        {
            $('.allTracks').html('');
            fav_tracks_table.draw();
        }
        else if(tabid == 'bintracks_tab')
        {
            $('.allTracks').html('');
            bin_tracks_table.draw();
        }
    });

    // recommended tracks
    $(document).on("click", "a.recommendedtrackEdit", function(event){
    $.ajax({
            url: this.id,
            type: 'get',
            success: function(data, textStatus, jqXHR) {
                $('#recommendedtrackModal').html(data).modal();
            },
            complete: function() { // fires after the ajax is fully complete
                recommendedtrack_validate();
            },
            beforeSend: function(request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
            }
        }); 
    });

    function recommendedtrack_validate()
    {
            $("form#recommendedtrackForm").validate({
                rules: {
                            name: {required : true},
                            file: {required : true},
                            artwork_file: {required : true},
                            background_image: {required : true},
                        },
                messages:{
                            name: {required:"Please enter recommended track name"},
                            file: {required:"Please select file"},
                            artwork_file: {required:"Please select artwork file"},
                            background_image: {required:"Please select background image"}
                        },
                errorPlacement: function(error, element) {
                    if (element.attr("name") == "file" || element.attr("name") == "artwork_file" || element.attr("name") == "background_image" ) {
                      error.insertAfter(element.parent());
                    } else {
                      error.insertAfter(element);
                    }
                  }
            });
    }

    $(document).on("click", "#addrecommendedtrack", function(event){
    $.ajax({
            url: base_url+'/admin/recommended_tracks/create',
            type: 'get',
            success: function(data, textStatus, jqXHR) {
                $('#recommendedtrackModal').html(data).modal();
            },
            complete: function() { // fires after the ajax is fully complete
                recommendedtrack_validate();
            },
            beforeSend: function(request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
            }
        })
    });


    // $(".js-example-basic-multiple").select2();

    $(".js-example-responsive").select2({
      maximumSelectionLength: 1,
      width: '100%',
      minimumInputLength: 3,
      placeholder: 'Artist name',


        ajax: {
            url: base_url+'/admin/get-artist',
            type: "GET",
            delay: 100,
            data: function (params) {

                var queryParameters = {
                    term: params.term
                }
                return queryParameters;
            },
            processResults: function (data) {
                console.log(data);
                return {

                    results: $.map(data.artistList, function (item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
            }
        }

    });


    $(document).on("click", "#RejectTrack", function(event){
        var id = $(this).attr('data-id'); 
        $.ajax({
                url: base_url+'/admin/discover-reject-track',
                data: {id:id},
                type: 'post',
                success: function(data, textStatus, jqXHR) {
                    window.location.href = location.href;
                },
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                }
            }); 
    });


});