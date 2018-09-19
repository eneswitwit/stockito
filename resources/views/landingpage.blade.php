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
<header id="home">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/landingpage/logo.png') }}" alt="image" height="40px">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#site-nav"
                    aria-controls="site-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="site-nav">
                <ul class="navbar-nav text-sm-left ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pricing">Pricing</a>
                    </li>
                    <li class="nav-item text-center">
                        <a href="/login" class="btn align-middle btn-outline-primary my-2 my-lg-0">Login</a>
                    </li>
                    <li class="nav-item text-center">
                        <a href="/register" class="btn align-middle btn-primary my-2 my-lg-0">Sign Up</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <section class="jumbotron-two">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-5">
                    <h1 class="display-5">Smart media manager</h1>
                    <p class="text-muted mb-3">
                        Our mantra is: Create the smartest tool for managing media files and their licenses.
                        With Stockito you have an effective media storage with an excepetional license manager to keep
                        track of your licenses. It's for you, your company and the creatives who work for you.</p>
                    <p>
                        <a href="/register" class="btn btn-xl btn-primary">Get started free</a>
                    </p>
                </div>
                <div class="col-12 col-md-7 my-3 my-md-lg">
                    <img src="{{ asset('images/landingpage/screen2.jpg') }}" alt="image" class="img-fluid">
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
        <div class="row">
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
        </div>
    </div>
</div>
<div class="section" id="features">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-sm-8">
                <div class="browser-window limit-height my-5 mr-0 mr-sm-5">
                    <div class="top-bar">
                        <div class="circles">
                            <div class="circle circle-red"></div>
                            <div class="circle circle-yellow"></div>
                            <div class="circle circle-blue"></div>
                        </div>
                    </div>
                    <div class="content">
                        <img src="{{ asset('images/landingpage/dashboard.png') }}" alt="image">
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="media">
                    <div class="media-body">
                        <div style="text-align: center;"><img src="{{ asset('images/landingpage/perfect.png') }}"
                                                              height="150px"></div>
                        <h3 class="mt-0">Award-winning Dashboard</h3>
                        <p> A perfect overview of all the licenses, media files and activites of your brand
                            collaborators.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
<div class="section bg-light py-lg" id="pricing">
    <div class="container">
        <div class="section-title text-center mt-0 mb-5">
            <h3>Choose your plan</h3>
            <p>Simple pricing. No hidden charges. Choose a plan fit your needs</p>
        </div>
        <div class="row">
            @foreach($plans as $plan)
            <div class="col-lg-4">
                <div class="card pricing">
                    <div class="card-body">
                        <small class="text-muted"> {{ $plan->product->name }} </small>
                        <h5 class="card-title">$ {{ $plan->price }}</h5>
                        <p class="card-text">
                        </p>
                        <ul class="list-unstyled">
                            <li>{{ $plan->product->storage/1000000000 }} GB Storage</li>
                            <li>Team Collaboration</li>
                            <li>Analytics &amp; Reports</li>
                        </ul>
                        <p></p>
                        <a href="/register/brand" class="btn btn-xl btn-outline-primary">Choose this plan</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
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
<div class="section bg-light mt-4" id="footer">
    <div class="container">
        <div class="row">
            <div class="col-sm-4"><img src="{{ asset('images/landingpage/logo.png') }}" height="50px"
                                       alt="Stockito Logo">
                <p class="mt-3 ml-1 text-muted">Media storage with license manager </p>
            </div>
            <div class="col-sm-2">
                <ul class="list-unstyled footer-links ml-1">
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <div class="col-sm-2">
                <ul class="list-unstyled footer-links ml-1">
                    <li><a href="#">Terms</a></li>
                    <li><a href="#about">Privacy</a></li>
                </ul>
            </div>
            <div class="col-sm-2">
                <ul class="list-unstyled footer-links ml-1">
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">Linkedin</a></li>
                </ul>
            </div>
            <div class="col-sm-2">
                <a href="#home" class="btn btn-sm btn-outline-primary ml-1">Go to Top</a>
            </div>
        </div>
        <div class=" text-center mt-4">
            <small class="text-muted">Copyright ©
                <script type="text/javascript">
                    document.write(new Date().getFullYear());
                </script>
                2018
                All rights reserved. Stockito.
            </small>
        </div>
    </div>
</div>
<div id="viewPortSize" style="background-color: rgb(0, 0, 0); color: rgb(255, 255, 255); font-size: 12px;"
     class="bottom_right"><br></div>
<iframe scrolling="no" frameborder="0" allowtransparency="true" style="display: none;"></iframe>
<iframe id="rufous-sandbox" scrolling="no" frameborder="0" allowtransparency="true" allowfullscreen="true"
        style="position: absolute; visibility: hidden; display: none; width: 0px; height: 0px; padding: 0px; border: none;"
        title="Twitter analytics iframe" src="./stockito/saved_resource.html"></iframe>
</body>
</html>