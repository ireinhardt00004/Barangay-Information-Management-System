function navScroll(){
    var isValid = true;
    let nav = document.getElementById("navbar"); 
    let logo = document.getElementById("cstm-nav-brand");
    let current_path = window.location.toString().split('/');
    var current_file = current_path.at(-1);
    let pages = ['home','membership'];
    pages.forEach(page => {
        if(current_file == page){
            isValid = true;
        }
    });
    if(isValid){
        if(document.body.scrollTop > 70 || document.documentElement.scrollTop > 70) {
            nav.classList.add('moved-nav');
            logo.style = 'transition:.3s; font-size: 15px;font-weight:bold;';
        }else{
            nav.classList.remove('moved-nav')
            logo.style = 'transition:.4s; font-size: 14px;';
        }
    }
}

window.onscroll = () =>{
    navScroll();
};

