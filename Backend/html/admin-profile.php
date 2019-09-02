<?php include "header.php"; ?>

  <body class="admin">
    <!--[if lt IE 10]>
      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <div class="animsition">

      <?php include "part-admin-navbar.php"; ?>

      <div class="background-image parallax-window" data-parallax="scroll" data-bleed="50" data-image-src="images/admin/bgd_image.jpg">
        <div class="black-gradient"></div><!-- /.black-gradient -->
        <div class="container-fluid">
          <div class="stats">
            <div class="row">
              <div class="col-md-12">
                <ul>
                  <li>
                    <span class="number">698</span>
                    <span class="title">Following</span>
                  </li>
                  <li>
                    <span class="number">1020</span>
                    <span class="title">Fans</span>
                  </li>
                  <li>
                    <span class="number">9825</span>
                    <span class="title">Likes</span>
                  </li>
                  <li>
                    <span class="number">98</span>
                    <span class="title">Tracks</span>
                  </li>
                </ul>
              </div><!-- /.col-md-12 -->
            </div><!-- /.row -->
          </div><!-- /.stats -->
        </div><!-- /.container-fluid -->
      </div><!-- /.background-image background-cover -->

      <section id="content">
         <div class="left-side">
          <section id="artist">
            <div class="image-wrapper">
              <img src="images/admin/img_artist.jpg" class="img-circle" alt="Artist name" />
              <div class="circle-star">
                <i class="fa fa-star"></i>
              </div><!-- /.star -->
            </div><!-- /.image-wrapper -->
            <h2>Artist Name</h2>
            <div class="socials-wrapper">
              <ul class="socials">
                <li>
                  <a href="#">
                    <div class="vertical-center">
                      <i class="fa fa-soundcloud"></i>
                    </div><!-- /.vertical-center -->
                  </a>
                </li>
                <li>
                  <a href="#">
                    <div class="vertical-center">
                      <i class="fa fa-facebook"></i>
                    </div><!-- /.vertical-center -->
                  </a>
                </li>
                <li>
                  <a href="#">
                    <div class="vertical-center">
                      <i class="fa fa-twitter"></i>
                    </div><!-- /.vertical-center -->
                  </a>
                </li>
                <li>
                  <a href="#">
                    <div class="vertical-center">
                      <i class="fa fa-youtube"></i>
                    </div><!-- /.vertical-center -->
                  </a>
                </li>
                <li>
                  <a href="#">
                    <div class="vertical-center">
                      <i class="fa fa-instagram"></i>
                    </div><!-- /.vertical-center -->
                  </a>
                </li>
              </ul><!-- /.socials -->
            </div><!-- /.socials-wrapper -->
            <a href="#" class="red-button">Follow me</a>
            <ul class="description">
              <li><i class="fa fa-link"></i><a href="#">www.mywebsite.com</a></li>
              <li><i class="fa fa-map-marker"></i>Reading, United Kingdom</li>
              <li>
                <i class="fa fa-music"></i>
                <ul class="tags">
                  <li><a href="#">Electro</a></li>
                  <li><a href="#">EDM</a></li>
                  <li><a href="#">Trance</a></li>
                </ul><!-- /.tags -->
              </li>
              <div class="clearfix"></div><!-- /.clearfix -->
            </ul><!-- /.desciption -->
          </section><!-- /#artist -->
          <section id="artist-bio">
            <div class="title-wrapper">
              <h2>Artist Bio</h2>
            </div><!-- /.title-wrapper -->
            <p>In 1999 and 2000, Tiësto collaborated with Ferry Corsten to create Gouryella. His 2000 remix of Delerium's "Silence" featuring Sarah McLachlan exposed him to more mainstream audiences.</p>
            <p>In 2001, he released his first solo album, In My Memory. The title track is a remix of an original song written by and featuring Nicola Hitchcock.</p>
            <p>This album gave him several major hits that launched his career. He was voted World No. 1 DJ by DJ Magazine in its annual Top 100 DJs readership poll consecutively for three years from 2002–0</p>
          </section><!-- /#artist-bio -->
          <section id="donate">
            <a href="#" class="donate-link">
              <i class="fa fa-heart"><h2>Donate</h2><i class="fa fa-music"></i></i>
            </a>
            <p>This artist has worked hard to provide you with their music for free. <a href="#">Why not give a little bit of love back?</a></p>
          </section><!-- /#donate -->
          <div class="clearfix"></div><!-- /.clearfix -->
        </div><!-- /.left-side -->
        <div class="center-side">
          <div class="container-fluid">
            <div class="row">
              <div class="col-xs-12">
                <nav class="navbar navbar-default">
                <div class="container-fluid">
                  <!-- Brand and toggle get grouped for better mobile display -->
                  <div class="navbar-header">

                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navigation" aria-expanded="false">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                    </button><span class="menu visible-xs">Menu</span>
                  </div>

                  <!-- Collect the nav links, forms, and other content for toggling -->
                  <div class="collapse navbar-collapse" id="main-navigation">
                    <ul class="nav navbar-nav">
                      <li><a href="admin-dashboard.php">Dashboard</a></li>
                      <li><a href="admin-billing-and-plans.php">Plans & Billing</a></li>
                      <li class="active"><a href="admin-profile.php">Profile</a></li>
                    </ul>
                  </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
              </nav>

              <section id="profile">
                <form action="#">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="box">
                        <div class="row">
                          <div class="col-xs-12">
                            <h2>Your profile Details</h2>
                          </div><!-- /.col-xs-12 -->
                        </div><!-- /.row -->
                        <div class="row">
                          <div class="col-lg-4">
                            <img src="images/admin/img_artist.jpg" class="img-circle" alt="Lorem ipsum" />
                            <div class="clearfix"></div><!-- /.clearfix -->
                            <a href="#" class="red-button">Edit image</a>
                          </div><!-- /.col-lg-4 -->
                          <div class="col-lg-8">
                            <div class="row">
                              <div class="col-xs-12">
                                <div id="profile-owl" class="owl-carousel owl-theme">
                                  <div class="item">
                                    <img src="images/admin/img_page-background.jpg" alt="" />
                                  </div>
                                  <div class="item">
                                    <img src="images/admin/img_page-background.jpg" alt="" />
                                  </div>
                                  <div class="item">
                                    <img src="images/admin/img_page-background.jpg" alt="" />
                                  </div>
                                  <div class="item">
                                    <img src="images/admin/img_page-background.jpg" alt="" />
                                  </div>
                                  <div class="item">
                                    <img src="images/admin/img_page-background.jpg" alt="" />
                                  </div>
                                  <div class="item">
                                    <img src="images/admin/img_page-background.jpg" alt="" />
                                  </div>
                                </div>

                                <!-- <div class="customNavigation">
                                  <a class="btn prev">Previous</a>
                                  <a class="btn next">Next</a>
                                  <a class="btn play">Autoplay</a>
                                  <a class="btn stop">Stop</a>
                                </div> -->
                              </div><!-- /.col-xs-12 -->
                            </div><!-- /.row -->
                            <div class="row">
                              <div class="col-xs-12">
                                <a href="#" class="red-button">Edit banner</a>
                                <a href="#" class="black-button">Templates</a>
                              </div><!-- /.col-xs-12 -->
                            </div><!-- /.row -->
                          </div><!-- /.col-lg-8 -->
                        </div><!-- /.row -->
                        <div class="row">
                          <div class="col-xs-12">
                            <label for="artistName">Artist name</label>
                            <input type="text" id="artistName" name="artistName" required />
                          </div><!-- /.col-xs-12 -->
                        </div><!-- /.row -->
                        <div class="row">
                          <div class="col-lg-6">
                            <label for="firstName">First name</label>
                            <input type="text" id="firstName" name="firstName" required />
                          </div><!-- /.col-lg-6 -->
                          <div class="col-lg-6">
                            <label for="surname">Surname</label>
                            <input type="text" id="surname" name="surname" required />
                          </div><!-- /.col-lg-6 -->
                        </div><!-- /.row -->
                        <div class="row">
                          <div class="col-xs-12">
                            <label for="emailAddress">Email address</label>
                            <input type="email" id="emailAddress" name="emailAdress" required />
                          </div><!-- /.col-xs-12 -->
                        </div><!-- /.row -->
                        <div class="row">
                          <div class="col-lg-6">
                            <label for="country">Country</label>
                             <select name="country" id="country" required>
                              <option value="value1">Value 1</option>
                              <option value="value2" selected>Value 2</option>
                              <option value="value3">Value 3</option>
                            </select>
                          </div><!-- /.col-lg-6 -->
                          <div class="col-lg-6">
                            <label for="town">Town / City</label>
                             <select name="town" id="town" required>
                              <option value="value1">Value 1</option>
                              <option value="value2" selected>Value 2</option>
                              <option value="value3">Value 3</option>
                            </select>
                          </div><!-- /.col-lg-6 -->
                        </div><!-- /.row -->
                        <div class="row">
                          <div class="col-xs-12">
                            <label for="website">Website</label>
                            <input type="text" name="website" id="website" required />
                          </div><!-- /.col-xs-12 -->
                        </div><!-- /.row -->
                        <div class="row">
                          <div class="col-xs-12">
                            <label for="genre">Genre (You can pick upto 3)</label>
                          </div><!-- /.col-xs-12 -->
                        </div><!-- /.row -->
                        <div class="row">
                          <div class="col-sm-6">
                            <input type="checkbox" name="genre" value="Electronic"> <span class="label-checkbox">Electronic</span><br>
                            <input type="checkbox" name="genre" value="EDM"> <span class="label-checkbox">EDM</span><br>
                            <input type="checkbox" name="genre" value="Dubstep"> <span class="label-checkbox">Dubstep</span><br>
                            <input type="checkbox" name="genre" value="Trance"> <span class="label-checkbox">Trance</span><br>
                            <input type="checkbox" name="genre" value="Glitch"> <span class="label-checkbox">Glitch</span><br>
                          </div><!-- /.col-lg-3 col-sm-6 -->
                          <div class="col-sm-6">
                            <input type="checkbox" name="genre" value="Electronic"> <span class="label-checkbox">Electronic</span><br>
                            <input type="checkbox" name="genre" value="EDM"> <span class="label-checkbox">EDM</span><br>
                            <input type="checkbox" name="genre" value="Dubstep"> <span class="label-checkbox">Dubstep</span><br>
                            <input type="checkbox" name="genre" value="Trance"> <span class="label-checkbox">Trance</span><br>
                            <input type="checkbox" name="genre" value="Glitch"> <span class="label-checkbox">Glitch</span><br>
                          </div><!-- /.col-lg-3 col-sm-6 -->
                        </div><!-- /.row -->
                        <div class="row">
                          <div class="col-xs-12">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" required />
                          </div><!-- /.col-xs-12 -->
                        </div><!-- /.row -->
                        <div class="row">
                          <div class="col-xs-12">
                            <label for="passwordConfirm">Confirm password</label>
                            <input type="password" name="passwordConfirm" id="passwordConfirm" required />
                          </div><!-- /.col-xs-12 -->
                        </div><!-- /.row -->
                      </div><!-- /.box -->
                    </div><!-- /.col-md-6 -->
                    <div class="col-md-6">
                      <div class="box">
                        <div class="row">
                          <div class="col-xs-12">
                            <h2>Socials links</h2>
                          </div><!-- /.col-xs-12 -->
                        </div><!-- /.row -->
                        <div class="row">
                          <div class="col-xs-12">
                            <label for="soundcloudLink">SoundCloud</label>
                            <input type="url" name="soundcloudLink" id="soundcloudLink" />
                          </div><!-- /.col-xs-12 -->
                        </div><!-- /.row -->
                        <div class="row">
                          <div class="col-xs-12">
                            <label for="facebookLink">Facebook</label>
                            <input type="url" name="facebookLink" id="facebookLink" />
                          </div><!-- /.col-xs-12 -->
                        </div><!-- /.row -->
                        <div class="row">
                          <div class="col-xs-12">
                            <label for="twitterLink">Twitter</label>
                            <input type="url" name="twitterLink" id="twitterLink" />
                          </div><!-- /.col-xs-12 -->
                        </div><!-- /.row -->
                        <div class="row">
                          <div class="col-xs-12">
                            <label for="youtubeLink">YouTube</label>
                            <input type="url" name="youtubeLink" id="youtubeLink" />
                          </div><!-- /.col-xs-12 -->
                        </div><!-- /.row -->
                        <div class="row">
                          <div class="col-xs-12">
                            <label for="instagramLink">Instagram</label>
                            <input type="url" name="instagramLink" id="instagramLink" />
                          </div><!-- /.col-xs-12 -->
                        </div><!-- /.row -->
                      </div><!-- /.box -->
                      <div class="box release-promotion">
                        <div class="row">
                          <div class="col-xs-12">
                            <h2>Label Release Promotion</h2>
                          </div><!-- /.col-xs-12 -->
                        </div><!-- /.row -->
                        <div class="row">
                          <div class="col-lg-6">
                            <label for="trackName">Track Name</label>
                            <input type="text" id="trackName" name="trackName" required />
                          </div><!-- /.col-lg-6 -->
                          <div class="col-lg-6">
                            <label for="recordLabel">Record Label</label>
                            <input type="text" id="recordLabel" name="recordLabel" required />
                          </div><!-- /.col-lg-6 -->
                        </div><!-- /.row -->
                        <div class="row">
                          <div class="col-xs-12">
                            <label for="linkPurchaseTrack">Link to Purchase The Track</label>
                            <input type="url" name="linkPurchaseTrack" id="linkPurchaseTrack" />
                          </div><!-- /.col-xs-12 -->
                        </div><!-- /.row -->
                        <div class="row">
                          <div class="col-xs-12">
                            <label for="uploadTrack">Upload Track / Album Image</label>
                            <input type="file" />
                          </div><!-- /.col-xs-12 -->
                        </div><!-- /.row -->
                        <div class="row">
                          <div class="col-xs-12">
                            <label for="genre">Genre (You can pick upto 3)</label>
                          </div><!-- /.col-xs-12 -->
                        </div><!-- /.row -->
                        <div class="row">
                          <div class="col-sm-6">
                            <input type="checkbox" name="genre" value="Electronic"> <span class="label-checkbox">Electronic</span><br>
                            <input type="checkbox" name="genre" value="EDM"> <span class="label-checkbox">EDM</span><br>
                            <input type="checkbox" name="genre" value="Dubstep"> <span class="label-checkbox">Dubstep</span><br>
                            <input type="checkbox" name="genre" value="Trance"> <span class="label-checkbox">Trance</span><br>
                            <input type="checkbox" name="genre" value="Glitch"> <span class="label-checkbox">Glitch</span><br>
                          </div><!-- /.col-lg-3 col-sm-6 -->
                          <div class="col-sm-6">
                            <input type="checkbox" name="genre" value="Electronic"> <span class="label-checkbox">Electronic</span><br>
                            <input type="checkbox" name="genre" value="EDM"> <span class="label-checkbox">EDM</span><br>
                            <input type="checkbox" name="genre" value="Dubstep"> <span class="label-checkbox">Dubstep</span><br>
                            <input type="checkbox" name="genre" value="Trance"> <span class="label-checkbox">Trance</span><br>
                            <input type="checkbox" name="genre" value="Glitch"> <span class="label-checkbox">Glitch</span><br>
                          </div><!-- /.col-lg-3 col-sm-6 -->
                        </div><!-- /.row -->
                      </div><!-- /.box -->
                    </div><!-- /.col-md-6 -->
                  </div><!-- /.row -->
                  <div class="row">
                    <div class="col-xs-12">
                      <div class="confirm-buttons">
                        <a href="#" class="delete-account">Delete account? Click here</a>
                        <input type="submit" value="Save profile" />
                      </div><!-- /.confirm-buttons -->
                    </div><!-- /.row -->
                  </div><!-- /.col-xs-12 -->
                </form>
              </section><!-- /#profile -->
              </div><!-- /.col-xs-12 -->
            </div><!-- /.row -->
          </div><!-- /.container-fluid -->
        </div><!-- /.center-side -->
        <div class="right-side">
          <section id="send-demo" class="hidden-sm hidden-xs">
            <h2>Send us your<br><span>demo</span></h2>
            <a href="#">Send track</a>
          </section><!-- /#send-demo -->
          <section id="label-release" class="hidden-sm hidden-xs">
            <h2>Label Release</h2>
            <div class="box">
              <div class="row">
                <div class="col-sm-4">
                  <img src="images/admin/img_track-cover.jpg" alt="" />
                </div><!-- /.col-sm-3 -->
                <div class="col-sm-8">
                  <h3>Track name</h3>
                  <span class="record-label">Record label</span>
                  <span class="genre">Genre</span>
                </div><!-- /.col-sm-9 -->
              </div><!-- /.row -->
              <div class="row">
                <div class="col-xs-12">
                  <a href="#" class="red-button">Listen & Buy here</a>
                </div><!-- /.col-xs-12 -->
              </div><!-- /.row -->
            </div><!-- /.box -->
          </section><!-- /#label-release -->
          <section id="following">
            <h2>Following</h2>
            <div class="box">
              <ul>
                <li>
                  <img src="images/admin/img_artist-header.jpg" alt="#" class="img-circle" />
                  <div class="details">
                    <h3>James Ansell</h3>
                    <i class="fa fa-users"></i><span>3265</span>
                  </div><!-- /.details -->
                </li>
                <li>
                  <img src="images/admin/img_artist-header.jpg" alt="#" class="img-circle" />
                  <div class="details">
                    <h3>James Ansell</h3>
                    <i class="fa fa-users"></i><span>3265</span>
                  </div><!-- /.details -->
                </li>
                <li>
                  <img src="images/admin/img_artist-header.jpg" alt="#" class="img-circle" />
                  <div class="details">
                    <h3>James Ansell</h3>
                    <i class="fa fa-users"></i><span>3265</span>
                  </div><!-- /.details -->
                </li>
              </ul>
              <a href="#" class="view-all">View all</a>
            </div><!-- /.box -->
          </section><!-- /#following -->
          <div class="clearfix"></div><!-- /.clearfix -->
        </div><!-- /.right-side -->
        <div class="clearfix"></div><!-- /.clearfix -->
      </section><!-- /.content -->

      <footer>
        <div class="container-fluid">
          <div class="col-sm-6 col-xs-12">
            <ul>
              <li>
                  <a href="#">
                      <div class="vertical-center">
                          <i class="fa fa-soundcloud"></i>
                      </div><!-- /.vertical-center -->
                  </a>
              </li>
              <li>
                  <a href="#">
                      <div class="vertical-center">
                          <i class="fa fa-facebook"></i>
                      </div><!-- /.vertical-center -->
                  </a>
              </li>
              <li>
                  <a href="#">
                      <div class="vertical-center">
                          <i class="fa fa-twitter"></i>
                      </div><!-- /.vertical-center -->
                  </a>
              </li>
              <li>
                  <a href="#">
                      <div class="vertical-center">
                          <i class="fa fa-youtube"></i>
                      </div><!-- /.vertical-center -->
                  </a>
              </li>
              <li>
                  <a href="#">
                      <div class="vertical-center">
                          <i class="fa fa-instagram"></i>
                      </div><!-- /.vertical-center -->
                  </a>
              </li>
            </ul>
          </div><!-- /.col-sm-6 -->
          <div class="col-sm-6 col-xs-12">
            <span>© 2016 Sore Thumb Media All Rights Reserved</span>
          </div><!-- /.col-sm-6 -->
        </div><!-- /.container-fluid -->
      </footer>

    </div><!-- /.animsition -->

<?php include "footer.php"; ?>
