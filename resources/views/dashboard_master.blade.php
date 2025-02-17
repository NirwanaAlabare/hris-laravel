
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">

    <style>
         body {
            font-family: "Libre Baskerville", serif;
            font-weight: 400;
            font-size: 16px;
            line-height: 30px;
            background-color: #ffffff;
            overflow-x:hidden;
            color: #ababab;
            width: 100%;
        }
        .site-wrapper-outline {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding-left: 20px;
            width: 100%;
            margin: 0;
            padding: 0;
            flex-direction: row;
            border-top: 4px solid #fdbf00;
        }
        .site-wrapper-inline {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding-left: 20px;
            width: 100%;
            margin: 0;
            padding: 0;
            flex-direction: row;
            padding-top: 100px;
        }
        .mt20{
            margin-top:20px;
        }
        ::-webkit-scrollbar {
            width: 10px;
            background-color: #FFF;

        }

        ::-webkit-scrollbar-thumb {
            background-color: #ffffff00;
            background-image: -webkit-linear-gradient(45deg,rgba(255, 255, 255, .2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .2) 50%, rgba(255, 255, 255, .2) 75%, transparent 75%, transparent);
        }


            .hexagon-item:first-child {
                margin-left: 0;
            }

            .hexagon-item {
                cursor: pointer;
                width: 200px;
                height: 173.20508px;
                float: left;
                margin-left: -29px;
                z-index: 0;
                position: relative;
                -webkit-transform: rotate(30deg);
                -moz-transform: rotate(30deg);
                -ms-transform: rotate(30deg);
                -o-transform: rotate(30deg);
                transform: rotate(30deg);
            }
            .hexagon-item:first-child {
                margin-left: 0;
            }
            .hexagon-item:hover {
                z-index: 1;
            }
            .hexagon-item:hover .hex-item:last-child {
                opacity: 1;
                -webkit-transform: scale(1.3);
                -moz-transform: scale(1.3);
                -ms-transform: scale(1.3);
                -o-transform: scale(1.3);
                transform: scale(1.3);
            }
            .hexagon-item:hover .hex-item:first-child {
                opacity: 1;
                -webkit-transform: scale(1.2);
                -moz-transform: scale(1.2);
                -ms-transform: scale(1.2);
                -o-transform: scale(1.2);
                transform: scale(1.2);
            }
            .hexagon-item:hover .hex-item:first-child div:before,
            .hexagon-item:hover .hex-item:first-child div:after {
                    height: 5px;
            }
                .hexagon-item:hover .hex-item div::before,
                .hexagon-item:hover .hex-item div::after {
                background-color: #9E3900;
            }
            .hexagon-item:hover .hex-content svg {
                -webkit-transform: scale(0.97);
                -moz-transform: scale(0.97);
                -ms-transform: scale(0.97);
                -o-transform: scale(0.97);
                transform: scale(0.97);
            }

            .page-home .hexagon-item:nth-last-child(1),
            .page-home .hexagon-item:nth-last-child(2),
            .page-home .hexagon-item:nth-last-child(3) {
                -webkit-transform: rotate(30deg) translate(87px, -80px);
                -moz-transform: rotate(30deg) translate(87px, -80px);
                -ms-transform: rotate(30deg) translate(87px, -80px);
                -o-transform: rotate(30deg) translate(87px, -80px);
                transform: rotate(30deg) translate(87px, -80px);
            }

            .hex-item {
                position: absolute;
                top: 0;
                left: 50px;
                width: 100px;
                height: 173.20508px;
            }
            .hex-item:first-child {
                z-index: 0;
                -webkit-transform: scale(0.9);
                -moz-transform: scale(0.9);
                -ms-transform: scale(0.9);
                -o-transform: scale(0.9);
                transform: scale(0.9);
                -webkit-transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
                -moz-transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
                -o-transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
                transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            }
            .hex-item:last-child {
                transition: all 0.3s cubic-bezier(0.19, 1, 0.22, 1);
                z-index: 1;
            }
            .hex-item div {
                box-sizing: border-box;
                position: absolute;
                top: 0;
                width: 100px;
                height: 173.20508px;
                -webkit-transform-origin: center center;
                -moz-transform-origin: center center;
                -ms-transform-origin: center center;
                -o-transform-origin: center center;
                transform-origin: center center;
            }
            .hex-item div::before, .hex-item div::after {
                background-color: #fdbf00;
                content: "";
                position: absolute;
                width: 100%;
                height: 3px;
                -webkit-transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) 0s;
                -moz-transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) 0s;
                -o-transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) 0s;
                transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) 0s;
            }
            .hex-item div:before {
                top: 0;
            }
            .hex-item div:after {
            bottom: 0;
        }
            .hex-item div:nth-child(1) {
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -o-transform: rotate(0deg);
            transform: rotate(0deg);
        }
            .hex-item div:nth-child(2) {
            -webkit-transform: rotate(60deg);
            -moz-transform: rotate(60deg);
            -ms-transform: rotate(60deg);
            -o-transform: rotate(60deg);
            transform: rotate(60deg);
        }
            .hex-item div:nth-child(3) {
            -webkit-transform: rotate(120deg);
            -moz-transform: rotate(120deg);
            -ms-transform: rotate(120deg);
            -o-transform: rotate(120deg);
            transform: rotate(120deg);
        }
        .hex-content {
        color: #fff;
        display: block;
        height: 180px;
        margin: 0 auto;
        position: relative;
        text-align: center;
        transform: rotate(-30deg);
        width: 156px; }
        .hex-content .hex-content-inner {
            left: 50%;
            margin: -3px 0 0 2px;
            position: absolute;
            top: 50%;
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            -o-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%); }
        .hex-content .icon {
            display: block;
            font-size: 36px;
            line-height: 30px;
            margin-bottom: 11px; }
        .hex-content .title {
            display: block;
            font-family: "Open Sans", sans-serif;
            font-size: 14px;
            letter-spacing: 1px;
            line-height: 24px;
            text-transform: uppercase;
            color: #FFFFFF;
            font-weight: bold;
        }
        .hex-content svg {
            left: -7px;
            position: absolute;
            top: -13px;
            transform: scale(0.87);
            z-index: -1;
            -webkit-transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) 0s;
            -moz-transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) 0s;
            -o-transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) 0s;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) 0s; }
        .hex-content:hover {
            color: #fff; }

        .page-home .hexagon-item:nth-last-child(1), .page-home .hexagon-item:nth-last-child(2), .page-home .hexagon-item:nth-last-child(3) {
            -webkit-transform: rotate(30deg) translate(87px, -80px);
            -moz-transform: rotate(30deg) translate(87px, -80px);
            -ms-transform: rotate(30deg) translate(87px, -80px);
            -o-transform: rotate(30deg) translate(87px, -80px);
            transform: rotate(30deg) translate(87px, -80px);
        }
        /*------------------------------------------------
            Welcome Page
        -------------------------------------------------*/
        .author-image-large {
        position: absolute;
        right: 0;
        top: 0; }
        .author-image-large img {
            height: -webkit-calc(100vh - 4px);
            height: -moz-calc(100vh - 4px);
            height: calc(100vh - 4px); }


        @media (min-width: 1200px)
        {
        .col-lg-offset-2 {
            margin-left: 16.66666667%;
        }
        }

        @media (min-width: 1200px)
        {
        .col-lg-8 {
            width: 66.66666667%;
        }
        }

        .hexagon-item:first-child {
            margin-left: 0;
        }

        .pt-table.desktop-768 .pt-tablecell {
            padding-bottom: 110px;
            padding-top: 60px;
        }

        .hexagon-item:hover .icon i
        {
        color:#9E3900;
        transition:0.6s;

        }

        .hexagon-item:hover .title
        {
        -webkit-animation: focus-in-contract 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
                    animation: focus-in-contract 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
                    color:#9E3900;
        }

        @-webkit-keyframes focus-in-contract {
        0% {
            letter-spacing: 1em;
            -webkit-filter: blur(12px);
                    filter: blur(12px);
            opacity: 0;
        }
        100% {
            -webkit-filter: blur(0px);
                    filter: blur(0px);
            opacity: 1;
        }
        }
        @keyframes focus-in-contract {
        0% {
            letter-spacing: 1em;
            -webkit-filter: blur(12px);
                    filter: blur(12px);
            opacity: 0;
        }
        100% {
            -webkit-filter: blur(0px);
                    filter: blur(0px);
            opacity: 1;
        }
        }
        @media only screen and (max-width: 767px)
        {
        .hexagon-item {
            float: none;
            margin: 0 auto 50px;
        }
        .hexagon-item:first-child {
            margin-left: auto;
        }
        .page-home .hexagon-item:nth-last-child(1), .page-home .hexagon-item:nth-last-child(2), .page-home .hexagon-item:nth-last-child(3) {
            -webkit-transform: rotate(30deg) translate(0px, 0px);
            -moz-transform: rotate(30deg) translate(0px, 0px);
            -ms-transform: rotate(30deg) translate(0px, 0px);
            -o-transform: rotate(30deg) translate(0px, 0px);
            transform: rotate(30deg) translate(0px, 0px);
        }

        }

    .content-right-side {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        width: 100%;
        flex-wrap: wrap;
        padding: 20px;
        /* padding-bottom: 200px; */
        padding-right: 120px;

    }
    .centered-content {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        text-align: center;
        gap: 10px;
    }

    .header-brand-img {
        max-width: 40px;
        height: auto;
    }
    .centered-text {
        margin: 0;
        font-size: 1.4em;
        text-align: left;
        font-weight: bold;
        line-height: 0.9;
        color: #717278;
    }
    /* .header-globe-img {
        max-width: 320px;
        height: auto;
    } */

    .img-side {
    perspective: 1000px;
}

.header-globe-img {
    max-width: 320px;
    transition: transform 0.5s ease-in-out;
    transform-style: preserve-3d;
}

.header-globe-img:hover {
    transform: rotateY(20deg) rotateX(10deg) scale(1.1);
}
@keyframes rotate3D {
    0% { transform: rotateY(0deg) rotateX(0deg); }
    50% { transform: rotateY(20deg) rotateX(10deg); }
    100% { transform: rotateY(0deg) rotateX(0deg); }
}

.header-globe-img {
    animation: rotate3D 5s infinite ease-in-out;
}


    </style>


 <div class="site-wrapper-outline relative">
    <div class="site-wrapper-inline">
        <div class="pt-table desktop-768">
        <div class="pt-tablecell page-home relative">
            <div class="overlay"></div>
                    <div class="col-xs-12 col-md-offset-1 col-md-10 col-lg-offset-2 col-lg-8">
                        <div class="hexagon-menu clear">
                            <div class="hexagon-item">

                            </div>
                            <div class="hexagon-item">
                                <div class="hex-item">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                                <div class="hex-item">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                                <a  class="hex-content">
                                    <span class="hex-content-inner">
                                        <span class="icon">
                                            <i class="fa fa-bullseye"></i>
                                        </span>
                                        <span class="title">GIS</span>
                                    </span>
                                    <svg viewBox="0 0 173.20508075688772 200" height="200" width="174" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M86.60254037844386 0L173.20508075688772 50L173.20508075688772 150L86.60254037844386 200L0 150L0 50Z" fill="#fdbf00"></path></svg>
                                </a>
                            </div>
                            <div class="hexagon-item">
                                <div class="hex-item">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                                <div class="hex-item">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                                <a  class="hex-content" href="{{route('hris.dashboard.tes')}}">
                                    <span class="hex-content-inner">
                                        <span class="icon">
                                            <i class="fa fa-braille"></i>
                                        </span>
                                        <span class="title">HRIS</span>
                                    </span>
                                    <svg viewBox="0 0 173.20508075688772 200" height="200" width="174" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M86.60254037844386 0L173.20508075688772 50L173.20508075688772 150L86.60254037844386 200L0 150L0 50Z" fill="#fdbf00"></path></svg>
                                </a>
                            </div>
                            <div class="hexagon-item">

                            </div>
                            <div class="hexagon-item">
                                <div class="hex-item">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                                <div class="hex-item">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                                <a  class="hex-content">
                                    <span class="hex-content-inner">
                                        <span class="icon">
                                            <i class="fa fa-life-ring"></i>
                                        </span>
                                        <span class="title">DHS</span>
                                    </span>
                                    <svg viewBox="0 0 173.20508075688772 200" height="200" width="174" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M86.60254037844386 0L173.20508075688772 50L173.20508075688772 150L86.60254037844386 200L0 150L0 50Z" fill="#fdbf00"></path></svg>
                                </a>
                            </div>
                            <div class="hexagon-item">
                                <div class="hex-item">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                                <div class="hex-item">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                                <a  class="hex-content">
                                    <span class="hex-content-inner">
                                        <span class="icon">
                                            <i class="fa fa-clipboard"></i>
                                        </span>
                                        <span class="title">PSD</span>
                                    </span>
                                    <svg viewBox="0 0 173.20508075688772 200" height="200" width="174" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M86.60254037844386 0L173.20508075688772 50L173.20508075688772 150L86.60254037844386 200L0 150L0 50Z" fill="#fdbf00"></path></svg>
                                </a>
                            </div>
                            <div class="hexagon-item">

                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <div class="content-right-side">
        <h2 style="font-size: 40px">Enterprise Management Systems</h2>
        <div class="centered-content">
            <img src="{{ URL::asset('assets/images/brand/logo-transparent.png') }}" class="header-brand-img main-logo" alt="Sparic logo">
            <h2 class="centered-text">PT. NIRWANA ALABARE GARMENT</h2>
        </div>
        <div class="img-side">
            <img src="{{ URL::asset('assets/image/pngwing.com(1).png') }}" class="header-globe-img main-logo" alt="Sparic logo">
        </div>
    </div>
</div>
