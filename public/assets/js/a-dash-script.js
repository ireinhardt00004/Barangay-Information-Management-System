function rpass(id, plength = 16) {
    const lowercaseChars = 'abcdefghijklmnopqrstuvwxyz';
    const uppercaseChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const numberChars = '0123456789';
    const specialChars = '!@#$%^&*()_-+=<>?/[]{}|';

    const allChars = lowercaseChars + uppercaseChars + numberChars + specialChars;

    let password = '';
    for (let i = 0; i < plength; i++) {
        const randomIndex = Math.floor(Math.random() * allChars.length);
        password += allChars.charAt(randomIndex);
    }
    box = document.getElementById(id);
    box.value = password;
}



function edit_user(fn,ln,mn,email,ut,uid){
    Swal.fire({
        title: 'Edit User',
        html: `
        <form class="needs-validation" method="post" enctype="multipart/form-data">
            <input class="d-none" name="user_type" value="`+ut+`">
            <input class="d-none" name="uid" value="`+uid+`">
            <div class="">
                <div class="d-flex justify-content-center">
                    <div class=" -auto w-100">
                        <input class="form-control" type="text" placeholder="First name" name="fname" value="`+fn+`" autocomplete="off" required>
                    </div>
                    <div class="mx-2 w-100">
                         <input class="form-control" type="text" placeholder="Middle name" name="mname" value="`+mn+`" autocomplete="off" >
                    </div>
                    <div class="w-100">
                         <input class="form-control" type="text" placeholder="Last name" name="lname" value="`+ln+`" autocomplete="off" required>
                    </div>
                </div>
                <div class="mt-2 text-left">
                    <input class="form-control" type="email" placeholder="New user email" name="email" value="`+email+`" autocomplete="off" required>
                </div>
                <!-- <label style="font-size:14px;">Leave password empty if you don't want to change it.</label>
                <div class="mt-2 text-left d-flex">
                    <input class="form-control" id="pass" type="text" placeholder="New user password.." name="password" value="" autocomplete="off">
                    <button class="btn btn-sm btn-primary mx-1" onclick="rpass('pass');return false;"><i class="fa-solid fa-repeat"></i></button>
                </div>
                -->
            </div>
            <input class="btn btn-outline-success m-3" type="submit" name="edit_user" value="Save">
            <input class="btn btn-secondary m-3" type="button" onclick="swal.close();" value="Cancel">
        </form>
        `,
        showConfirmButton: false,
    });
}

// toast('Delete Success!',2300, 'rgb(0,160,0,0.8)');
function delete_user(ut,uid){
        Swal.fire({
            title: 'Do you confirm to delete this account?',
            html: `
            <form class="needs-validation" method="post" enctype="multipart/form-data">
                <input class="d-none" name="user_type" value="`+ut+`">
                <input class="d-none" name="uid" value="`+uid+`">
                <input class="btn btn-outline-danger m-3" type="submit" name="delete_user" value="I Confirm">
                <input class="btn btn-secondary m-3" type="button" onclick="swal.close();" value="Cancel">
            </form>
            `,
            showConfirmButton: false,
        });    
}

function delete_report(id){
    Swal.fire({
        title: 'Do you confirm to delete this report?',
        html: `
        <form class="needs-validation" method="post" enctype="multipart/form-data">
            <input class="d-none" name="report_id" value="`+id+`">
            <input class="btn btn-outline-danger m-3" type="submit" name="delete_report" value="I Confirm">
            <input class="btn btn-secondary m-3" type="button" onclick="swal.close();" value="Cancel">
        </form>
        `,
        showConfirmButton: false,
    });    
}

function delete_record(id){
    Swal.fire({
        title: 'Do you confirm to delete this record?',
        html: `
        <form class="needs-validation" method="post" enctype="multipart/form-data">
            <input class="d-none" name="record_id" value="`+id+`">
            <input class="btn btn-outline-danger m-3" type="submit" name="delete_record" value="I Confirm">
            <input class="btn btn-secondary m-3" type="button" onclick="swal.close();" value="Cancel">
        </form>
        `,
        showConfirmButton: false,
    });    
}

function delete_request(id){
    Swal.fire({
        title: 'Do you confirm to delete this request?',
        html: `
        <form class="needs-validation" method="post" enctype="multipart/form-data">
            <input class="d-none" name="request_id" value="`+id+`">
            <input class="btn btn-outline-danger m-3" type="submit" name="delete_request" value="I Confirm">
            <input class="btn btn-secondary m-3" type="button" onclick="swal.close();" value="Cancel">
        </form>
        `,
        showConfirmButton: false,
    });    
}


function create_nav(pages) {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    Swal.fire({
        title: 'Create new Navigation',
        html: `
        <form id="createNavForm" class="needs-validation" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="hidden" name="_token" value="${csrfToken}">
                <div class="d-flex justify-content-center">
                    <div class=" mx-auto w-100">
                        <input class="form-control" type="text" placeholder="Nav name" name="nav_name" value="" id="nav_namz" autocomplete="off" required>
                    </div>
                </div>
                <div class="mt-2 mx-auto w-100">
                    <label><b>Select page to be linked:</b></label>
                    <select name="nav_page" class="btn" style="outline:none; border:1px solid rgb(0,0,0,0.3);" required>
                        <option disabled selected>-- Select Page --</option>
                        ` + atob(pages) + `
                    </select>
                </div>
            </div>
            <input class="btn btn-outline-success mx-auto" type="submit" value="Create Nav">
            <input class="btn btn-secondary mx-auto" type="button" onclick="Swal.close();" value="Cancel">
        </form>
        `,
        showConfirmButton: false,
        didOpen: () => {
            document.getElementById('createNavForm').addEventListener('submit', function(event) {
                event.preventDefault();

                let formData = new FormData(this);

                fetch('/navs/store', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Navigation created successfully!'
                        }).then(() => {
                            location.reload(); // Reload the page to reflect changes
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while creating the navigation.'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while creating the navigation.'
                    });
                });
            });
        }
    });
}


function edit_nav(pages, nav, npage, nid){
    Swal.fire({
        title: 'Edit Navigation',
        html: `
        <form class="needs-validation" method="post" enctype="multipart/form-data">
            <input class="d-none" name="nav_id" value="`+nid+`">
            <div class="mb-3">
                <div class="d-flex justify-content-center">
                    <div class=" mx-auto w-100">
                        <input class="form-control" type="text" placeholder="Nav name" name="nav_name" value="`+nav+`" id="nav_namz" autocomplete="off" required>
                    </div>
                </div>
                <div class="mt-2 mx-auto w-100">
                    <label><b>Select page to be linked:</b></label>
                    <select name="nav_page" class="btn" style="outline:none; border:1px solid rgb(0,0,0,0.3);">
                        <option disabled selected>-- Select Page --</option>
                        `+atob(pages)+`
                    </select>
                </div>
            </div>
            <input class="btn btn-outline-success mx-auto" type="submit" name="edit_nav" value="Save changes">
            <input class="btn btn-secondary mx-auto" type="button" onclick="swal.close();" value="Cancel">
        </form>
        `,
        showConfirmButton: false,
    });
}


function delete_headerbtn(type,id){
    Swal.fire({
        title: 'Do you confirm to delete this?',
        html: `
        <form class="needs-validation" method="post" enctype="multipart/form-data">
            <input class="d-none" name="type" value="`+type+`">
            <input class="d-none" name="id" value="`+id+`">
            <input class="btn btn-outline-danger m-3" type="submit" name="delete_l" value="I Confirm">
            <input class="btn btn-secondary m-3" type="button" onclick="swal.close();" value="Cancel">
        </form>
        `,
        showConfirmButton: false,
    });    
}
function new_image() {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    Swal.fire({
        title: 'Add image',
        html: `
        <form id="imageForm" class="needs-validation" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="${csrfToken}">
            <div class="d-flex">
                <div class="container-fluid">
                    <div class="mx-auto rounded mt-1 mb-1" style="border:2px dashed rgb(0,0,0,0.4)!important; max-height:300px; max-width:300px; overflow:auto;">
                        <img class="img-fluid container p-0 rounded" src="./assets/imgs_uploads/bg-img1.png" id="alb-prev-img" draggable="false">
                    </div>
                    <label for="imageFilez-pr" class="mt-2 m-1 mb-2 d-block btn btn-outline-secondary">
                        <input onchange="sel_imgv2('alb-prev-img', 'imageFilez-pr');" class="form-control d-none" type="file" id="imageFilez-pr" name="imageFilez" accept="image/gif, image/png, image/jpeg">
                        Upload Image
                    </label>
                </div>
            </div>
            <input class="btn btn-outline-success m-3" type="button" onclick="submitImageForm();" value="Add Home image">
            <input class="btn btn-secondary m-3" type="button" onclick="swal.close();" value="Cancel">
        </form>
        `,
        showConfirmButton: false,
    });
}

function submitImageForm() {
    var formData = new FormData(document.getElementById('imageForm'));

    $.ajax({
        url: '/post-homeimg',  // Ensure this matches the route defined in Laravel
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            Swal.fire('Success', 'Image uploaded successfully!', 'success');
            // Optionally, you can refresh the page or update a part of the page with new data
            // location.reload(); // Uncomment to reload the page
        },
        error: function(xhr, status, error) {
            Swal.fire('Error', 'There was an error uploading the image.', 'error');
        }
    });
}
function new_logo() {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    Swal.fire({
        title: 'Add new logo',
        html: `
        <form id="logoForm" class="needs-validation" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="${csrfToken}">
            <div class="d-flex">
                <div class="container-fluid">
                    <div class="mx-auto rounded mt-1 mb-1" style="border:2px dashed rgb(0,0,0,0.4)!important; max-height:300px; max-width:300px; overflow:auto;">
                        <img class="img-fluid p-0 rounded" src="./assets/imgs/logo.png" id="alb-prev-img" draggable="false">
                    </div>
                    <label for="imageFilez-pr" class="mt-2 m-1 mb-2 d-block btn btn-outline-secondary">
                        <input onchange="sel_imgv2('alb-prev-img','imageFilez-pr');" class="form-control d-none" type="file" id="imageFilez-pr" name="imageFilez" accept="image/gif, image/png, image/jpeg">
                        Upload Image
                    </label>
                </div>
            </div>
            <input class="btn btn-outline-success m-3" type="button" onclick="submitLogoImageForm();" value="Save new logo">
            <input class="btn btn-secondary m-3" type="button" onclick="Swal.close();" value="Cancel">
        </form>
        `,
        showConfirmButton: false,
    });
}

function submitLogoImageForm() {
    var formData = new FormData(document.getElementById('logoForm'));

    $.ajax({
        url: '/post-headlogo', 
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            Swal.fire('Success', 'Logo uploaded successfully!', 'success');
            // Optionally, you can refresh the page or update a part of the page with new data
            // location.reload(); // Uncomment to reload the page
        },
        error: function(xhr, status, error) {
            Swal.fire('Error', 'There was an error uploading the Logo.', 'error');
        }
    });
}

function del_imgz(item_id) {
    Swal.fire({
        title: 'Continue delete image?',
        text: "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '',
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            let formData = new FormData();
            formData.append("item_id", item_id);
            formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/delete-homeimage', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Deleted!', 'Image has been deleted.', 'success');
                    // Optionally, you can refresh the page or update a part of the page with new data
                    location.reload(); // Reload the page to reflect the changes
                } else {
                    Swal.fire('Error', data.error, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'There was an error deleting the image.', 'error');
            });
        }
    });
}


function sel_imgv2(eid,iid){
    var file_path = document.getElementById(iid);
    var preview = document.getElementById(eid);

    var f_ext = file_path.value.split('.').at(-1);
    if(f_ext == 'jpg' || f_ext == 'gif' || f_ext == 'png' || f_ext == 'jpeg'){
      preview.src = URL.createObjectURL(file_path.files[0]);
      preview.classList.remove('cstm-hidden');
    }
}

function create_head_btn() {
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    Swal.fire({
        title: 'Create Header Button',
        html: `
        <form id="headBtnForm" class="needs-validation" method="post">
            <div class="mb-3">
                <input type="hidden" name="_token" value="${csrfToken}">
                <div class="d-flex justify-content-center">
                    <div class="mx-auto w-100">
                        <input class="mt-2 form-control" type="text" name="btn_name" placeholder="Button name" required>
                        <input class="mt-2 form-control" type="text" name="btn_url" placeholder="Link (ex: https://google.com)" required>
                        <div class="d-flex mx-auto form-check form-switch">
                            <input name="btn_outline" class="form-check-input" type="checkbox" role="switch" id="switch_outline">
                            <label class="mx-2 form-check-label" for="switch_outline">Is outline?</label>
                        </div>
                    </div>
                </div>
            </div>
            <input class="btn btn-outline-success mx-auto" type="button" onclick="submitHeadBtnForm()" value="Create button">
            <input class="btn btn-secondary mx-auto" type="button" onclick="swal.close();" value="Cancel">
        </form>
        `,
        showConfirmButton: false,
    });
}

function submitHeadBtnForm() {
    var formData = new FormData(document.getElementById('headBtnForm'));

    $.ajax({
        url: '/post-headbtn',  // Ensure this matches the route defined in Laravel
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            Swal.fire('Success', 'Button created successfully!', 'success');
            // Optionally, you can refresh the page or update a part of the page with new data
            // location.reload(); // Uncomment to reload the page
        },
        error: function(xhr, status, error) {
            Swal.fire('Error', 'There was an error creating the button.', 'error');
        }
    });
}

function delete_headerbtn(type,id){
        Swal.fire({
            title: 'Continue delete Header Button?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '',
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                let formData = new FormData();
                formData.append("item_id", id);
                formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
                fetch('/delete-headerbutton', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Deleted!', 'Image has been deleted.', 'success');
                        // Optionally, you can refresh the page or update a part of the page with new data
                        location.reload(); // Reload the page to reflect the changes
                    } else {
                        Swal.fire('Error', data.error, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'There was an error deleting the image.', 'error');
                });
            }
        });
    }
    function edit_head_btn(name, link, outline, id) {
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        Swal.fire({
            title: 'Edit Header Button',
            html: `
            <form id="headBtnForm" class="needs-validation" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="btn_id" value="${id}">
                <div class="mb-3">
                    <div class="d-flex justify-content-center">
                        <div class="mx-auto w-100">
                            <input class="mt-2 form-control" type="text" placeholder="Button name" name="btn_name" value="${name}" autocomplete="off" required>
                            <input class="mt-2 form-control" type="text" placeholder="Link (ex: https://google.com)" name="btn_url" value="${link}" autocomplete="off" required>
                            <div class="d-flex mx-auto form-check form-switch">
                                <input name="btn_outline" class="form-check-input" type="checkbox" role="switch" id="switch_outline" ${outline ? 'checked' : ''}>
                                <label class="mx-2 form-check-label" for="switch_outline">Is outline?</label>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-outline-success mx-auto" type="submit">Save changes</button>
                <button class="btn btn-secondary mx-auto" type="button" onclick="Swal.close();">Cancel</button>
            </form>
            `,
            showConfirmButton: false,
            didOpen: () => {
                document.getElementById('headBtnForm').addEventListener('submit', function(event) {
                    event.preventDefault(); 
                    submiteditHeadBtnForm();
                });
            }
        });
    }
    
    function submiteditHeadBtnForm() {
        var formData = new FormData(document.getElementById('headBtnForm'));
    
        $.ajax({
            url: '/update-headbtn',  
            type: 'PUT',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                Swal.fire('Success', 'Button updated successfully!', 'success');
                // Optionally, you can refresh the page or update a part of the page with new data
                location.reload(); // Uncomment to reload the page
            },
            error: function(xhr, status, error) {
                Swal.fire('Error', 'There was an error updating the button.', 'error');
            }
        });
    }
    
function create_footer_btn(){
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    Swal.fire({
        title: 'Add Link',
        html: `
        <form id="footerCreateForm" class="needs-validation" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="${csrfToken}">
        <div class="mb-3">
                <div class="d-flex justify-content-center">
                    <div class=" mx-auto w-100">
                        <input class="mt-2 form-control" type="text" name="link_gov" placeholder="Link (ex: https://tanza.gov.ph)" value="" autocomplete="off" >
                        <input class="mt-2 form-control" type="text" name="link_social" placeholder="Link (ex: https://facebook.com/barangay.trescruses.5)" value="" autocomplete="off" >
                        <input class="mt-2 form-control" type="text" name="link_contact" placeholder="Link (ex: https://tanza.gov.ph)" value="" autocomplete="off" >
                    </div>
                </div>
            </div>
            <input class="btn btn-outline-success mx-auto" onclick="submitFooterForm()" type="submit" name="add_linkz" value="Add links">
            <input class="btn btn-secondary mx-auto" type="button" onclick="swal.close();" value="Cancel">
        </form>
        `,
        showConfirmButton: false,
    });
}
function submitFooterForm() {
    var formData = new FormData(document.getElementById('footerCreateForm'));

    $.ajax({
        url: '/post-footer-links',  // Ensure this matches the route defined in Laravel
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            Swal.fire('Success', 'Links added successfully!', 'success');
            // Optionally, you can refresh the page or update a part of the page with new data
             location.reload(); // Uncomment to reload the page
        },
        error: function(xhr, status, error) {
            Swal.fire('Error', 'There was an error adding the links.', 'error');
        }
    });
    
}


function create_rtypes_btn(){
    Swal.fire({
        title: 'Add a Request Type',
        html: `
        <form class="needs-validation" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <div class="d-flex justify-content-center">
                    <div class=" mx-auto w-100">
                        <input class="mt-2 form-control" type="text" name="new_rtype" placeholder="Ex. Brgy ID" value="" autocomplete="off" >
                    </div>
                </div>
            </div>
            <input class="btn btn-outline-success mx-auto" type="submit" name="add_rtype" value="Add">
            <input class="btn btn-secondary mx-auto" type="button" onclick="swal.close();" value="Cancel">
        </form>
        `,
        showConfirmButton: false,
    });
}
function delete_footerlinkz(type,id){
    Swal.fire({
        title: 'Continue delete Footer Link?',
        text: "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '',
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            let formData = new FormData();
            formData.append("item_id", id);
            formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/delete-footerlinks', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Deleted!', 'Footer Link has been deleted.', 'success');
                    // Optionally, you can refresh the page or update a part of the page with new data
                    location.reload(); // Reload the page to reflect the changes
                } else {
                    Swal.fire('Error', data.error, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'There was an error deleting the image.', 'error');
            });
        }
    });
}

function edit_footer_btn(id, gov, social, contact){
    Swal.fire({
        title: 'Add Link',
        html: `
        <form class="needs-validation" method="post" enctype="multipart/form-data">
            <input class="d-none" name="id" value="`+id+`">
            <div class="mb-3">
                <div class="d-flex justify-content-center">
                    <div class=" mx-auto w-100">
                        <input class="mt-2 form-control" value="`+gov+`" type="text" name="link_gov" placeholder="Link (ex: https://tanza.gov.ph)" autocomplete="off" >
                        <input class="mt-2 form-control" value="`+social+`" type="text" name="link_social" placeholder="Link (ex: https://facebook.com/barangay.trescruses.5)" autocomplete="off" >
                        <input class="mt-2 form-control" value="`+contact+`" type="text" name="link_contact" placeholder="Link (ex: https://tanza.gov.ph)" autocomplete="off" >
                    </div>
                </div>
            </div>
            <input class="btn btn-outline-success mx-auto" type="submit" name="edit_linkz" value="Add links">
            <input class="btn btn-secondary mx-auto" type="button" onclick="swal.close();" value="Cancel">
        </form>
        `,
        showConfirmButton: false,
    });
}

function new_card(){
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    Swal.fire({
        title: 'Add Card',
        html: `
        <form class="needs-validation" id="newCardForms" method="post" enctype="multipart/form-data">
    <div class="d-flex">
        <input type="hidden" name="_token" value="${csrfToken}">
        <div class="container-fluid">
            <div class="mx-auto rounded mt-1 mb-1" style="border:2px dashed rgb(0,0,0,0.4)!important; max-height:300px; max-width:300px; overflow:auto;">
                <img class="img-fluid container p-0 rounded" src="./assets/imgs_uploads/img1.png" id="alb-prev-img" draggable="false">
            </div>
            <label for="imageFilez-pr" class="mt-2 m-1 mb-2 d-block btn btn-outline-secondary">
                <input onchange="sel_imgv2('alb-prev-img','imageFilez-pr');" class="form-control d-none" type="file" id="imageFilez-pr" name="imageFilez" accept="image/gif, image/png, image/jpeg">
                Upload Image
            </label>
            <div class="d-flex justify-content-center">
                <div class=" mx-auto w-100">
                    <input class="mt-2 form-control" value="" type="text" name="title" placeholder="Title:" autocomplete="off">
                    <input class="mt-2 form-control" value="" type="text" name="link" placeholder="Link (ex: https://tanza.gov.ph or /contacts)" autocomplete="off" >
                </div>
            </div>
        </div>
    </div>
    <input class="btn btn-outline-success m-3" type="submit" onclick="submitNewCardForm()" name="new_card" value="Add Card">
    <input class="btn btn-secondary m-3" type="button" onclick="swal.close();" value="Cancel">
</form> `,
        showConfirmButton: false,
    });
}
function submitNewCardForm() {
    var formData = new FormData(document.getElementById('newCardForms'));
    
    // Log form data for debugging
    for (var pair of formData.entries()) {
        console.log(pair[0]+ ', '+ pair[1]); 
    }

    $.ajax({
        url: '/post-newcards', 
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            Swal.fire('Success', 'New Card created successfully!', 'success');
            // Optionally, you can refresh the page or update a part of the page with new data
             location.reload(); // Uncomment to reload the page
        },
        error: function(xhr, status, error) {
            Swal.fire('Error', 'There was an error creating the card.', 'error');
        }
    });
}
function delete_headercards(type,id){
    Swal.fire({
        title: 'Continue delete Home Card?',
        text: "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '',
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Delete'
    }).then((result) => {
        if (result.isConfirmed) {
            let formData = new FormData();
            formData.append("item_id", id);
            formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/delete-headercard', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Deleted!', 'Card has been deleted.', 'success');
                    // Optionally, you can refresh the page or update a part of the page with new data
                    location.reload(); // Reload the page to reflect the changes
                } else {
                    Swal.fire('Error', data.error, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'There was an error deleting the image.', 'error');
            });
        }
    });
}
