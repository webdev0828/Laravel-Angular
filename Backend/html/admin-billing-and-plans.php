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

              <section id="your-payment-history">
                <div class="title-wrapper">
                  <h2 class="pull-left">Your Payment history</h2><!-- /.pull-left -->
                  <a href="#" class="refresh pull-right"><i class="fa fa-credit-card"></i> Update Payment Details</a>
                  <div class="clearfix"></div><!-- /.clearfix -->
                  <div class="table-header">
                    <div class="row">
                      <div class="col-md-3">
                        <span>Payment Date</span>
                      </div><!-- /.col-md-3 -->
                      <div class="col-md-3 text-center">
                        <span>Plan Purchased</span>
                      </div><!-- /.col-md-3 -->
                      <div class="col-md-3 text-center">
                        <span>Expires</span>
                      </div><!-- /.col-md-3 -->
                      <div class="col-md-3 text-center">
                        <span>Total Amount</span>
                      </div><!-- /.col-md-3 -->
                    </div><!-- /.row -->
                  </div><!-- /.table-header -->
                  <div class="table-body">
                    <div class="table-row">
                      <div class="row">
                        <div class="col-md-3">
                          <span>The Best Drop</span>
                        </div><!-- /.col-md-3 -->
                        <div class="col-md-3 text-center">
                          <span>Jan 01/16</span>
                        </div><!-- /.col-md-3 text-center -->
                        <div class="col-md-3 text-center">
                          <span>Jan 01/16</span>
                        </div><!-- /.col-md-3 text-center -->
                        <div class="col-md-3 text-center">
                          <span>25GBP</span>
                        </div><!-- /.col-md-3 text-center -->
                      </div><!-- /.row -->
                    </div><!-- /.table-row -->
                    <div class="table-row">
                      <div class="row">
                        <div class="col-md-3">
                          <span>The Best Drop</span>
                        </div><!-- /.col-md-3 -->
                        <div class="col-md-3 text-center">
                          <span>Jan 01/16</span>
                        </div><!-- /.col-md-3 text-center -->
                        <div class="col-md-3 text-center">
                          <span>Jan 01/16</span>
                        </div><!-- /.col-md-3 text-center -->
                        <div class="col-md-3 text-center">
                          <span>25GBP</span>
                        </div><!-- /.col-md-3 text-center -->
                      </div><!-- /.row -->
                    </div><!-- /.table-row -->
                    <div class="table-row">
                      <div class="row">
                        <div class="col-md-3">
                          <span>The Best Drop</span>
                        </div><!-- /.col-md-3 -->
                        <div class="col-md-3 text-center">
                          <span>Jan 01/16</span>
                        </div><!-- /.col-md-3 text-center -->
                        <div class="col-md-3 text-center">
                          <span>Jan 01/16</span>
                        </div><!-- /.col-md-3 text-center -->
                        <div class="col-md-3 text-center">
                          <span>25GBP</span>
                        </div><!-- /.col-md-3 text-center -->
                      </div><!-- /.row -->
                    </div><!-- /.table-row -->
                    <div class="table-row">
                      <div class="row">
                        <div class="col-md-3">
                          <span>The Best Drop</span>
                        </div><!-- /.col-md-3 -->
                        <div class="col-md-3 text-center">
                          <span>Jan 01/16</span>
                        </div><!-- /.col-md-3 text-center -->
                        <div class="col-md-3 text-center">
                          <span>Jan 01/16</span>
                        </div><!-- /.col-md-3 text-center -->
                        <div class="col-md-3 text-center">
                          <span>25GBP</span>
                        </div><!-- /.col-md-3 text-center -->
                      </div><!-- /.row -->
                    </div><!-- /.table-row -->
                  </div><!-- /.table-body -->
                </div><!-- /.title-wrapper -->
              </section><!-- /#your-payment-history -->
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
          <section id="activity-feed">
            <h2>Activity Feed</h2>
            <div class="box">
              <ul>
                <li>
                  <img src="images/admin/img_artist-header.jpg" alt="#" class="img-circle" />
                  <p><strong>James Ansell</strong> added a new track called <a href="#">Firestarter</a></p>
                </li>
                <li>
                  <img src="images/admin/img_artist-header.jpg" alt="#" class="img-circle" />
                  <p><strong>James Ansell</strong> added a new track called <a href="#">Firestarter</a></p>
                </li>
                <li>
                  <img src="images/admin/img_artist-header.jpg" alt="#" class="img-circle" />
                  <p><strong>James Ansell</strong> added a new track called <a href="#">Firestarter</a></p>
                </li>
                <li>
                  <img src="images/admin/img_artist-header.jpg" alt="#" class="img-circle" />
                  <p><strong>James Ansell</strong> added a new track called <a href="#">Firestarter</a></p>
                </li>
                <li>
                  <img src="images/admin/img_artist-header.jpg" alt="#" class="img-circle" />
                  <p><strong>James Ansell</strong> added a new track called <a href="#">Firestarter</a></p>
                </li>
                <li>
                  <img src="images/admin/img_artist-header.jpg" alt="#" class="img-circle" />
                  <p><strong>James Ansell</strong> added a new track called <a href="#">Firestarter</a></p>
                </li>
              </ul>
            </div><!-- /.box -->
          </section><!-- /#activity-feed -->
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