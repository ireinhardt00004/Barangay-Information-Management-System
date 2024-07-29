<header class="headerOne">



    <div id="drawerContainer" class="drawerContainer">
        @livewire('notification-component')
        <div id="notifDaw" class="notifDaw">
            <div id="" class="notifClose">
                <h4>Messages</h4>
                <span id="notifClose">X</span>
            </div>
            <div>
                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Veritatis odit molestiae
                dignissimos et asperiores a excepturi ut dolores nesciunt necessitatibus laudantium
                perspiciatis similique, labore quidem quisquam! Dolores eum quia beatae.
            </div>
        </div>
        <div id="dimmer" class="dimmer"></div>
    </div>
    
                <div class="burgerButton">
                    <button id="offcanvas">
                        <svg width="100%" height="100%" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                            stroke="#cbb782">
                            <g id="SVGRepo_bgCarrier" stroke-width="0" />
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />
                            <g id="SVGRepo_iconCarrier">
                                <path d="M4 18L20 18" stroke="#585756" stroke-width="2" stroke-linecap="round" />
                                <path d="M4 12L20 12" stroke="#585756" stroke-width="2" stroke-linecap="round" />
                                <path d="M4 6L20 6" stroke="#585756" stroke-width="2" stroke-linecap="round" />
                            </g>
                        </svg>
                    </button>
                </div>
                <div class="logoHeader">
                    <a href="">
                        
                        <img src="{{asset('sys_logo/logo.png')}}" alt="" width="40" height="40">
                        <div class="sysTitle">
                            <p>Brgy. Tres Cruses, Tanza, Cavite</p>
                        </div>
                    </a>
                </div>
                <div id="notifMess" class="notifMess">
                    <div>
                       
                        <button id="notifDawBTN">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path d="M12.0009 5C13.4331 5 14.8066 5.50571 15.8193 6.40589C16.832 7.30606 17.4009 8.52696 17.4009 9.8C17.4009 11.7691 17.846 13.2436 18.4232 14.3279C19.1606 15.7133 19.5293 16.406 19.5088 16.5642C19.4849 16.7489 19.4544 16.7997 19.3026 16.9075C19.1725 17 18.5254 17 17.2311 17H6.77066C5.47638 17 4.82925 17 4.69916 16.9075C4.54741 16.7997 4.51692 16.7489 4.493 16.5642C4.47249 16.406 4.8412 15.7133 5.57863 14.3279C6.1558 13.2436 6.60089 11.7691 6.60089 9.8C6.60089 8.52696 7.16982 7.30606 8.18251 6.40589C9.19521 5.50571 10.5687 5 12.0009 5ZM12.0009 5V3M9.35489 20C10.0611 20.6233 10.9888 21.0016 12.0049 21.0016C13.0209 21.0016 13.9486 20.6233 14.6549 20" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </g>
                            </svg>
                        </button>
                            @livewire('unread-notification-badge-counter')
                        {{-- <button id="MessDawBTN"><svg width="800px" height="800px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                
                                <g id="SVGRepo_bgCarrier" stroke-width="0" />
                
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />
                
                                <g id="SVGRepo_iconCarrier">
                                    <path
                                        d="M7.76953 4.58009C8.57706 3.74781 9.54639 3.08958 10.6178 2.64588C11.6892 2.20219 12.84 1.98233 13.9995 2.00001C18.4195 2.00001 21.9995 5.10005 21.9995 8.92005C21.9792 9.98209 21.7021 11.0234 21.1919 11.9551C20.6817 12.8867 19.9535 13.681 19.0696 14.27V16.75"
                                        stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M16 13.64C16 10.29 12.87 7.58008 9 7.58008C5.13 7.58008 2 10.29 2 13.64C2.01941 14.5684 2.26227 15.4785 2.70789 16.2931C3.1535 17.1077 3.78881 17.803 4.56006 18.3201V20.49C4.55903 20.7858 4.64489 21.0755 4.80701 21.3229C4.96912 21.5703 5.20032 21.7647 5.47192 21.8818C5.74353 21.999 6.04362 22.0338 6.33484 21.9819C6.62606 21.9301 6.89553 21.7938 7.10999 21.5901L9.10999 19.6901C12.94 19.6201 16 16.94 16 13.64Z"
                                        stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </g>
                
                            </svg></button> --}}
                
                    </div>
                </div>
                <style>
                    .notifDawHide {
                        display: none;
                    }
                
                    .drawerContainerShow {
                        display: block;
                        overflow-y: auto;
                    }
                
                    /* Additional styles to enhance the notification panel */
                    .notification-container {
                        position: fixed;
                        top: 50px;
                        right: 0;
                        width: 700px;
                        max-height: 80vh; /* Adjust this value as needed */
                        background: white;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        padding: 20px;
                        border-radius: 5px;
                        z-index: 1000;
                        overflow-y: auto;
                    }
                    .drawerContainerShow {
                    display: block;
                    position: fixed;
                    top: 0;
                    right: 0;
                    bottom: 0;
                    left: 0;
                    background: rgba(0, 0, 0, 0.5);
                    z-index: 999;
                    }
                    .drawerContainerShow {
                     display: block;
                    position: fixed;
                    top: 0;
                    right: 0;
                    bottom: 0;
                    left: 0;
                    background: rgba(0, 0, 0, 0.5);
                    z-index: 999;
                }
                </style>
                
            </header>
    