<?php

    $postdata = $req->post();
    $filedata = $req->files();

    $csrf = csrf_verify($postdata);

    $files_ok = true;
    if (!empty($filedata)) {
        $postdata['image'] = $req->upload_files('image');
        
dd($postdata);
        if (!empty($req->upload_errors))
            $files_ok = false;
    }

    if($csrf && $files_ok && $social->validate_insert($postdata)) {

        if (user_can('add_social_link')) {

            $social->insert($postdata);

            message_success("Record added successfully!");
            redirect($admin_route . '/' . $plugin_route);
        }

    } 
        
        if (!$csrf) 
            $social->errors['email'] = "Form expired!";
        
        set_value('errors', $social->errors);



