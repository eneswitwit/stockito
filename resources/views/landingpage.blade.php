<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Stockito</title>
    <meta property="og:title" content="Stockito - Media storage with license manager ">
    <meta property="og:type" content="website">
    <meta property="og:image" content="images/screen.jpg">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

</head>
<body class="overflow-hidden">
<header id="home" style="background: linear-gradient(90deg, rgb(55,87,197), rgb(180,69,256));">
    <nav class="navbar navbar-expand-lg"
         style="background: linear-gradient(90deg, rgb(55,87,197), rgb(180,69,256)) !important; -webkit-box-shadow: none !important;-moz-box-shadow: none !important; box-shadow: none !important;">
        <div class="container">
            <a class="navbar-brand" href="#" style="margin: 30px;">
                <img src="{{ asset('images/landingpage/logo.png') }}" alt="image" height="30px">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#site-nav"
                    aria-controls="site-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="site-nav">
                <ul class="navbar-nav text-sm-left ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features" style="color: #fff !important;">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pricing" style="color: #fff !important;">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a href="/login" class="nav-link" style="color: #fff !important;">Login</a>
                    </li>
                    <li class="nav-item text-center">
                        <a href="/register" class="btn align-middle btn-primary my-2 my-lg-0"
                           style="color: #fff !important; background-color: rgb(74,204,255); border-color: rgb(74,204,255); border-radius: 50px; padding: 10px 30px;">Sign
                            Up</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <section class="jumbotron-two">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-5">
                    <h1 class="display-5 mb-0" style="color: rgb(255,255,255);"> All-In-One Cloud-Based </h1>
                    <h1 class="display-5 mb-4" style="font-weight: 100 !important; color: rgb(255,255,255);"> Storage and License Manager </h1>
                    <p class="text-muted mb-3" style="color: rgb(255,255,255) !important; font-weight: 100;">
                        It's never been easier to store and share all your digital media in one place, with <strong>
                            Stockito.</strong>
                        Never again miss an expiry date or lose a file on someone's hard disk.

                    </p>
                    <p style="padding-top: 40px;">
                        <a href="/register" class="btn align-middle btn-primary my-2 my-lg-0"
                           style="color: #fff !important; background-color: rgb(74,204,255); border-color: rgb(74,204,255); border-radius: 50px; padding: 10px 30px;">
                            Get Started </a>
                    </p>
                </div>
                <div class="col-12 col-md-7">
                    <img src="{{ asset('images/landingpage/1.png') }}" alt="image" class="img-fluid">
                </div>
            </div>
        </div>
    </section>
    <div class="bg-shape"></div>
    <div class="bg-circle"></div>
    <div class="bg-circle-two"></div>
</header>
<div class="section bg-light pt-lg">
    <div class="container">
    <!--<div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="media mb-5">
                    <div class="media-icon d-flex mr-3"><img
                                src="{{ asset('images/landingpage/easy_adoptation.png') }}" height="90px"
                                width="70px"></div>
                    <div class="media-body">
                        <h5 class="mt-0">Easy Adaptation</h5> Invite your co-workers, employees and the creative
                        companies working for you, all in one click. More over use our free support to integrate your
                        old system into ours.
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="media mb-5">
                    <div class="media-icon d-flex mr-3"><img src="{{ asset('images/landingpage/fast_upload.png') }}"
                                                             height="90px"
                                                             width="70px"></div>
                    <div class="media-body">
                        <h5 class="mt-0">Fast Upload with FTP</h5> Upload all your media files easy and fast via FTP.
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="media mb-5">
                    <div class="media-icon d-flex mr-3"><img
                                src="{{ asset('images/landingpage/meta_data_extraction.png') }}" height="85px"
                                width="70px"></div>
                    <div class="media-body">
                        <h5 class="mt-0">Meta Data Extraction</h5> All information concerning your media files will be
                        automatically read from the meta data and saved.
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="media mb-5">
                    <div class="media-icon d-flex mr-3"><img src="{{ asset('images/landingpage/license_manager.png') }}"
                                                             height="90px"
                                                             width="70px"></div>
                    <div class="media-body">
                        <h5 class="mt-0">License Manager</h5> Upload the bills of the licenses, mark the expiration date
                        and remind your co-workers and creatives. We make sure you will have the perfect overview of
                        your licenses.
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="media mb-5">
                    <div class="media-icon d-flex mr-3"><img
                                src="{{ asset('images/landingpage/proven_technology.png') }}" height="90px"
                                width="70px"></div>
                    <div class="media-body">
                        <h5 class="mt-0">Proven Technology</h5> Our high-end technology stack and carefully developed
                        software provide a secure system for your data.
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="media mb-5">
                    <div class="media-icon d-flex mr-3"><img src="{{ asset('images/landingpage/overview.png') }}"
                                                             height="90px" width="70px">
                    </div>
                    <div class="media-body">
                        <h5 class="mt-0">100% Overview</h5> All your media files and their licenses, all the people
                        involved in one place. It was never easier and cheaper to manage your media.
                    </div>
                </div>
            </div>
        </div>-->
        <div class="row vertical-align">
            <div class="col-md-6">
                <h4 style="margin: 20px;"> Lightning fast upload & keywording </h4>
                <p>
                    Use our simple drag and drop uploader or ftp to rapidly upload files to your
                    new digital media library. You can choose keywording manually or Stockito
                    does it automatically for you.
                </p>
            </div>
            <div class="col-md-6">
                <img src="{{ asset('images/landingpage/2.png') }}" alt="image" class="img-fluid">
            </div>
        </div>
        <div class="row vertical-align">
            <div class="col-md-6">
                <img src="{{ asset('images/landingpage/3.png') }}" alt="image" class="img-fluid">
            </div>
            <div class="col-md-6">
                <h4 style="margin: 20px;"> License editing and management </h4>
                <p>
                    Gain a perfect bides-eye view of your licenses and manage them with ease.
                    Define the type of your license, upload licence bills and always be aware
                    of their statuses.
                </p>
            </div>
        </div>
        <div class="row vertical-align">
            <div class="col-md-6">
                <h4 style="margin: 20px;"> Clever user access </h4>
                <p>
                    Easily grant access to all your media buyers, coworkers and creative
                    agencies working for you and define what rights they have.
                </p>
            </div>
            <div class="col-md-6">
                <img src="{{ asset('images/landingpage/4.png') }}" alt="image" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<div class="section" id="features" style="background: linear-gradient(90deg,rgb(180,69,256),rgb(55,87,197));">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <img src="{{ asset('images/landingpage/5.png') }}" alt="image" class="img-fluid">
            </div>
            <div class="col-md-6">
                <div class="media">
                    <div class="media-body">
                        <h3 class="mb-5" style="color: white;">Your brand's own online media library</h3>
                        <p class="mb-5" style="color: white;">
                            Use the vibrant digital media library tools, search and exercise easy access
                            to any media at your disposal and save license costs bby re-using your media files.
                        </p>
                        <p style="padding-top: 30px;">
                            <a href="/register" class="btn align-middle btn-primary my-2 my-lg-0"
                               style="color: #fff !important; background-color: rgb(74,204,255); border-color: rgb(74,204,255); border-radius: 50px; padding: 10px 30px;">
                                Get Started </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--
<div class="section bg-light py-lg" id="brandscreatives">
    <div class="container">
        <div class="section-title text-center mt-0 mb-5">
            <h3>Who are you?</h3>
            <p>We differentiate betweens brands and creatives. </p>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="card pricing">
                    <div class="card-body">
                        <small class="text-muted" style="font-size:1.5rem;">CREATIVE</small>
                        <h5 class="card-title">Free</h5>
                        <p class="card-text">
                        </p>
                        <ul class="list-unstyled">
                            <li>Access unlimited brands</li>
                            <li> Manage media files of brands</li>
                            <li>License Manager</li>
                            <li>Team Collaboration</li>
                        </ul>
                        <p></p>
                        <a href="/register/creative" class="btn btn-xl btn-outline-primary">Sign up</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card pricing">
                    <div class="card-body">
                        <small class="text-muted" style="font-size:1.5rem;">BRAND</small>
                        <h5 class="card-title">From $9</h5>
                        <p class="card-text">
                        </p>
                        <ul class="list-unstyled">
                            <li> Storage for your media files</li>
                            <li> License Manager</li>
                            <li>Team Collaboration</li>
                            <li>Analytics &amp; Reports</li>
                        </ul>
                        <p></p>
                        <a href="#pricing" class="btn btn-xl btn-primary">See pricing</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section">
    <div class="container">
        <div class="section-title text-center">
            <h3>What our customers say</h3>
            <p>This is why our customers love us.</p>
        </div>
        <div class="row" style="border: 0px solid rgba(0,0,0,.125); border-radius: .25rem;">
            <div class="col-md-2">
            </div>
            <div class="col-md-4">
                <div style="text-align: center;">
                    <img src="{{ asset('images/landingpage/customer1.jpg') }}" height="240px" width="250px"
                         style="border-radius:60%; padding: 20px;">
                    <p><b> Thomas Albrecht</b></p>
                    <p> CEO of Liebesmensch GmbH in Zürich</p>
                </div>
            </div>
            <div class="col-md-4 col-md-offset-2" style="padding-top:30px;">
                I own a clothing brand specialized in jewellery. Since I have a lot of freelancing photographers,
                creative agencies and my own creatives in my company I got overwhelmed by the data. More over for
                compliance and legal reasons I need to keep track of the licenses and their usage. Stockito was just
                perfect. It's the perfect management tool for each brand which faces the same problems as I did. Their
                support is just great and I was overwhelmed by how easy it is.
            </div>
        </div>
    </div>
</div>
-->
<div class="section bg-light py-lg" id="pricing">
    <div class="container">
        <div class="section-title text-center mt-0 mb-5">
            <h3>Choose <strong> Your Plan </strong></h3>
            <p>Simple pricing depending on required storage space. </p>
            <p><strong> No hidden charges or setup fees. </strong></p>
        </div>
        <div class="row">
            @foreach($plans as $plan)
                <div class="col-lg-4">
                    <div class="card pricing">
                        <div class="card-header" style="background: transparent !important; color: black;">
                            <small class="text-muted"> {{ $plan->product->name }} </small>
                            <h5 class="card-title mb-0" style="color: rgb(149,70,229);"> {{ $plan->price }} € </h5>
                            <p class="mt-0"> Per year </p>
                        </div>
                        <div class="card-body">

                            <ul class="list-unstyled">
                                <li><strong> {{ $plan->product->storage/1000000000 }} GB Storage </strong></li>
                                <li>Team Collaboration</li>
                                <li>Analytics &amp; Reports</li>
                            </ul>
                            <p></p>
                            <p style="padding-top: 30px;">
                                <a href="/register" class="btn align-middle btn-primary my-2 my-lg-0"
                                   style="color: #fff !important; background-color: rgb(74,204,255); border-color: rgb(74,204,255); border-radius: 50px; padding: 10px 30px;">
                                    Get Started </a>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row" style="text-align: center;">
            <div class="col-md-12">
                <p><strong> All prices subject to VAT </strong></p>
                <h6 style="margin: 30px;"> Need even more space? <strong> <a href="#" style="color: rgb(180,69,256) !important;"> Get in touch </a> for personal packages. </strong>
                </h6>
            </div>
        </div>
    </div>

</div>
<!--
<div class="section" id="signup">
    <div class="container">
        <div class="section-title text-center">
            <h3>Start your free trial</h3>
            <p>Signup now. Its free and takes less than 3 minutes</p>
        </div>
        <div class="row justify-content-md-center">
            <div class="col col-md-5">
                <form lpformnum="1">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Full Name" autocomplete="off"
                               style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAkCAYAAADo6zjiAAAAAXNSR0IArs4c6QAAAbNJREFUWAntV8FqwkAQnaymUkpChB7tKSfxWCie/Yb+gbdeCqGf0YsQ+hU95QNyDoWCF/HkqdeiIaEUqyZ1ArvodrOHxanQOiCzO28y781skKwFW3scPV1/febP69XqarNeNTB2KGs07U3Ttt/Ozp3bh/u7V7muheQf6ftLUWyYDB5yz1ijuPAub2QRDDunJsdGkAO55KYYjl0OUu1VXOzQZ64Tr+IiPXedGI79bQHdbheCIAD0dUY6gV6vB67rAvo6IxVgWVbFy71KBKkAFaEc2xPQarXA931ot9tyHphiPwpJgSbfe54Hw+EQHMfZ/msVEEURjMfjCjbFeG2dFxPo9/sVOSYzxmAwGIjnTDFRQLMQAjQ5pJAQkCQJ5HlekeERxHEsiE0xUUCzEO9AmqYQhiF0Oh2Yz+ewWCzEY6aYKKBZCAGYs1wuYTabKdNNMWWxnaA4gp3Yry5JBZRlWTXDvaozUgGTyQSyLAP0dbb3DtQlmcan0yngT2ekE9ARc+z4AvC7nauh9iouhpcGamJeX8XF8MaClwaeROWRA7nk+tUnyzGvZrKg0/40gdME/t8EvgG0/NOS6v9NHQAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Email Address" autocomplete="off"
                               style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAkCAYAAADo6zjiAAAAAXNSR0IArs4c6QAAAbNJREFUWAntV8FqwkAQnaymUkpChB7tKSfxWCie/Yb+gbdeCqGf0YsQ+hU95QNyDoWCF/HkqdeiIaEUqyZ1ArvodrOHxanQOiCzO28y781skKwFW3scPV1/febP69XqarNeNTB2KGs07U3Ttt/Ozp3bh/u7V7muheQf6ftLUWyYDB5yz1ijuPAub2QRDDunJsdGkAO55KYYjl0OUu1VXOzQZ64Tr+IiPXedGI79bQHdbheCIAD0dUY6gV6vB67rAvo6IxVgWVbFy71KBKkAFaEc2xPQarXA931ot9tyHphiPwpJgSbfe54Hw+EQHMfZ/msVEEURjMfjCjbFeG2dFxPo9/sVOSYzxmAwGIjnTDFRQLMQAjQ5pJAQkCQJ5HlekeERxHEsiE0xUUCzEO9AmqYQhiF0Oh2Yz+ewWCzEY6aYKKBZCAGYs1wuYTabKdNNMWWxnaA4gp3Yry5JBZRlWTXDvaozUgGTyQSyLAP0dbb3DtQlmcan0yngT2ekE9ARc+z4AvC7nauh9iouhpcGamJeX8XF8MaClwaeROWRA7nk+tUnyzGvZrKg0/40gdME/t8EvgG0/NOS6v9NHQAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" autocomplete="off"
                               style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAkCAYAAADo6zjiAAAAAXNSR0IArs4c6QAAAbNJREFUWAntV8FqwkAQnaymUkpChB7tKSfxWCie/Yb+gbdeCqGf0YsQ+hU95QNyDoWCF/HkqdeiIaEUqyZ1ArvodrOHxanQOiCzO28y781skKwFW3scPV1/febP69XqarNeNTB2KGs07U3Ttt/Ozp3bh/u7V7muheQf6ftLUWyYDB5yz1ijuPAub2QRDDunJsdGkAO55KYYjl0OUu1VXOzQZ64Tr+IiPXedGI79bQHdbheCIAD0dUY6gV6vB67rAvo6IxVgWVbFy71KBKkAFaEc2xPQarXA931ot9tyHphiPwpJgSbfe54Hw+EQHMfZ/msVEEURjMfjCjbFeG2dFxPo9/sVOSYzxmAwGIjnTDFRQLMQAjQ5pJAQkCQJ5HlekeERxHEsiE0xUUCzEO9AmqYQhiF0Oh2Yz+ewWCzEY6aYKKBZCAGYs1wuYTabKdNNMWWxnaA4gp3Yry5JBZRlWTXDvaozUgGTyQSyLAP0dbb3DtQlmcan0yngT2ekE9ARc+z4AvC7nauh9iouhpcGamJeX8XF8MaClwaeROWRA7nk+tUnyzGvZrKg0/40gdME/t8EvgG0/NOS6v9NHQAAAABJRU5ErkJggg==&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-xl btn-block btn-primary">GET STARTED FOR FREE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
-->
<div class="section mt-0" id="footer" style="background:linear-gradient(90deg, rgb(55,87,197), rgb(180,69,256)) !important;"">
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-4">
                    <a href="#contact" style="color: white !important;">Contact</a>
                    </div>
                    <div class="col-sm-4">
                    <a href="#" style="color: white !important;">Terms</a>
                    </div>
                    <div class="col-sm-4">
                    <a href="#about" style="color: white !important;">Privacy</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-4" style="text-align: center;">
                <img src="{{ asset('images/landingpage/logo.png') }}" height="20px" alt="Stockito Logo">
            </div>
            <div class="col-sm-4" style="text-align: right;">
                <!--<ul class="list-unstyled ml-1">
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">Linkedin</a></li>
                </ul>-->
            </div>
            <!--<div class="col-sm-2">
                <a href="#home" class="btn btn-sm btn-outline-primary ml-1">Go to Top</a>
            </div>-->
        </div>
        <div class=" text-center mt-4">
            <small class="text-muted">Copyright ©
                <script type="text/javascript">
                    document.write(new Date().getFullYear());
                </script>
                Stockito
            </small>
        </div>
    </div>
</div>

<iframe scrolling="no" frameborder="0" allowtransparency="true" style="display: none;"></iframe>
<iframe id="rufous-sandbox" scrolling="no" frameborder="0" allowtransparency="true" allowfullscreen="true"
        style="position: absolute; visibility: hidden; display: none; width: 0px; height: 0px; padding: 0px; border: none;"
        title="Twitter analytics iframe" src="./stockito/saved_resource.html"></iframe>
</body>
</html>