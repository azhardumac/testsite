<?php
header("Content-Type:text/css");
$color = "#f0f"; // Change your Color Here
$secondColor = "#ff8"; // Change your Color Here

function checkhexcolor($color)
{
    return preg_match('/^#[a-f0-9]{6}$/i', $color);
}

if (isset($_GET['color']) and $_GET['color'] != '') {
    $color = "#" . $_GET['color'];
}

if (!$color or !checkhexcolor($color)) {
    $color = "#336699";
}


function checkhexcolor2($secondColor)
{
    return preg_match('/^#[a-f0-9]{6}$/i', $secondColor);
}

if (isset($_GET['secondColor']) and $_GET['secondColor'] != '') {
    $secondColor = "#" . $_GET['secondColor'];
}

if (!$secondColor or !checkhexcolor2($secondColor)) {
    $secondColor = "#336699";
}
?>



.header.menu-fixed .header__bottom, .header__top, .footer__top, .second--bg{
    background-color: <?php echo $secondColor; ?> !important;
}

.custom--accordion .accordion-button:not(.collapsed)::after{
    color: <?php echo $secondColor; ?> !important;
}

.header-login-btn{
    border: 1px solid <?php echo $color; ?>;
    background-color: <?php echo $color; ?>;
}

.testimonial-card__client .thumb{
    border: 3px solid <?php echo $color; ?>;
}

.btn--base, .roadmap-wrapper::after, .single-roadmap .roadmap-dot, .subscribe-form .subscribe-btn, .scroll-to-top, .custom--accordion .accordion-button:not(.collapsed), .video-btn::before, .video-btn::after, .video-btn, .loadbar, .cursor, .custom--file-upload::before, .header .main-menu li .sub-menu, .header .main-menu li .sub-menu li a::before, .btn-outline--base:hover, .btn--primary, .qr-code-copy-form input[type="submit"], .pagination .page-item.active .page-link, .custom--bg, .modal-header .btn-close:hover, .preloader div:before{
    background-color: <?php echo $color; ?> !important;
}
.single-roadmap:hover, .deposit-card:hover, .d-widget:hover{
    border-color: <?php echo $color; ?> !important;
}

.feature-card__icon i, .testimonial-card::before, .date-unit-list .single-unit span, .page-breadcrumb li:first-child::before, b.text-success, .custom--cl, .custom--accordion .accordion-button::after, .contact-card__icon i, .header .main-menu li a:hover, .header .main-menu li a:focus, .header-login-btn:hover i, .header .top-info a, .text--base, .d-widget__icon i, .btn-outline--base  {
    color: <?php echo $color; ?> !important;
}
a.btn-outline--base:hover {
    color: #000  !important;
}
.header .main-menu li .sub-menu li a:hover {
    color: #000 !important;
}

.feature-card, .team-card{
    box-shadow: 0 0 0px 2px <?php echo $color; ?> !important;
}
.feature-card:hover, .team-card:hover{
    box-shadow: 0 5px 25px 2px <?php echo $color; ?> !important;
}

.social-links li a:hover, .input-group-text{
    background-color: <?php echo $color; ?> !important;
    border-color: <?php echo $color; ?> !important;
}

.cursor-follower, .btn-outline--base{
    border: 1px solid <?php echo $color; ?> !important;
}

.account-wrapper, .header .main-menu li .sub-menu, .deposit-preview-card, .single-roadmap, .testimonial-card, .deposit-card{
    border: 2px solid <?php echo $color; ?>2b !important;
}

.account-thumb-area .account-thumb, .custom--accordion .accordion-item, .custom--border{
    border: 1px solid <?php echo $color; ?> !important; 
}
.form-control:placeholder-shown,
.country-code .input-group-prepend .input-group-text {
    border-color: rgba(255, 255, 255, 0.15) !important;
}

.form-control, .form-control:focus{
    border-color: <?php echo $color; ?> !important;
}

.roadmap-wrapper .single-roadmap:nth-child(even)::before{
    border-color: transparent transparent transparent <?php echo $color; ?> !important;
}

.roadmap-wrapper .single-roadmap::before{
    border-color: transparent <?php echo $color; ?> transparent transparent;
}

.roadmap-wrapper .single-roadmap:hover::before {
    border-color: transparent <?php echo $color; ?> transparent transparent; 
}

.deposit-card:hover {
    box-shadow: 0 5px 20px 1px  <?php echo $color; ?>;
}

.glass--bg, .custom--card, .single-roadmap, .testimonial-slider .prev, .testimonial-slider .next, .contact-form, .contact-card, .d-widget, .header-login-btn:hover, .feature-card::before, .team-card::after{
    background-color: <?php echo $color; ?>2b;
}

a.btn--base:hover, .btn--base:hover, [class*="btn--"]:not(.btn--link):not(.btn--light):not(.btn) {
    color: #000 !important;
}
.btn--base:hover {
    box-shadow: 0 2px 5px 1px <?php echo $color; ?>ba;
}
.custom--table {
    background-color: <?php echo $color; ?>15;
}

.count-wrapper {
    border-color: <?php echo $color; ?>;
    box-shadow: 0 0 15px 0px <?php echo $color; ?>;
    --shadow-color: <?php echo $color; ?>;
}

.testimonial-card:hover {
    border-color: <?php echo $color; ?> !important;
}

.d-widget__amount::after {
    background-color: <?php echo $color; ?>15;
}

.d-widget:hover .d-widget__amount::after {
    background-color: <?php echo $color; ?>;
}

.d-widget {
    border-color: <?php echo $color; ?>;
}
.d-widget:hover {
    box-shadow: 0 3px 10px 1px <?php echo $color; ?>85;
}
.highlighted-text {
    background-color: <?php echo $color; ?>;
    box-shadow: 0 0 5px 1px <?php echo $color; ?>;
}
.custom--card {
    border-color: <?php echo $color; ?>15;
}

.profile-thumb .avatar-edit label {
    background-color: <?php echo $color; ?>;
}

.subscribe-wrapper,
.subscribe-form,
.contact-form,
.contact-card {
    border-color: <?php echo $color; ?> !important;
}

@supports (backdrop-filter: none) {
	.glass--bg, .custom--card, .single-roadmap, .testimonial-slider .prev, .testimonial-slider .next, .contact-form, .contact-card, .d-widget, .glass--bg-two, .testimonial-card, .subscribe-wrapper, .account-wrapper {
        background-color: <?php echo $color; ?>33 !important;
    }
}

@supports not (backdrop-filter: none) {
    .glass--bg, .custom--card, .single-roadmap, .testimonial-slider .prev, .testimonial-slider .next, .contact-form, .contact-card, .d-widget, .glass--bg-two, .testimonial-card, .subscribe-wrapper, .account-wrapper {
        background-color: <?php echo $secondColor; ?>d6 !important;
    }
}


.video-btn:hover{
    color: black;
}