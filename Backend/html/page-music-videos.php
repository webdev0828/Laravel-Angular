<?php include "header.php"; ?>

  <body class="page">
    <!--[if lt IE 10]>
      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <div class="animsition">

      <?php include "part-admin-navbar.php"; ?>

      <div class="background-image background-cover" style="background-image: url('images/admin/bgd_image.jpg');">
        <div class="vertical-center">
          <div class="container">
            <div class="row">
              <div class="col-xs-12">
                <h1>Music video</h1>
              </div><!-- /.col-xs-12 -->
            </div><!-- /.row -->
          </div><!-- /.container -->
        </div><!-- /.vertical-center -->
      </div><!-- /.background-image background-cover -->

      <div class="about-stm">
        <div class="container">
          <div class="row">
            <div class="col-md-10 col-md-offset-1">
              <h2>Sore Thumb Media is a <b>multi-promotional platform</b> aimed at producers of all genres in electronic music.</h2>
            </div><!-- /.col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 -->
          </div><!-- /.row -->
        </div><!-- /.container -->
      </div><!-- /.about-stm -->

      <div class="banner" class="parallax-window" data-parallax="scroll" data-bleed="10" data-image-src="images/homepage/bgd_artists-say.jpg">
        <div class="container">
          <div class="row">
            <div class="col-md-10 col-md-offset-1">
              <section id="banner-music-videos" class="banner-inner">
                <div class="row">
                  <div class="col-xs-12">
                    <h2>Spotlight Music Video</h2>
                  </div><!-- /.col-xs-12 -->
                </div><!-- /.row -->
                <div class="row">
                  <div class="row-height">
                    <div class="col-md-8 col-sm-height col-sm-top">
                      <div class="background-cover" style="background-image: url(images/img_cover-nomask.jpg);"></div><!-- /.background-cover -->
                    </div><!-- /.col-md-8 col-sm-height col-sm-top -->
                    <div class="col-md-4 col-sm-height col-sm-top">
                      <div class="video-track-constant">
                        <div class="assets">
                            <div class="row">
                              <div class="col-xs-7">
                                <span class="author">James Ansell</span>
                              </div><!-- /.col-xs-7 -->
                              <div class="col-xs-5">
                                <span class="player-counter"><img src="images/icon_play.svg" alt="Play icon" /> 184</span>
                              </div><!-- /.col-xs-5 -->
                            </div><!-- /.row -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <h3>Track Name Here</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam odio sint beatae aliquam, unde deserunt repellendus. Quas, veritatis! Ullam quibusdam qui distinctio dolor, nisi accusantium eaque eveniet iusto facere deleniti!</p>
                                </div><!-- /.col-xs-12 -->
                            </div><!-- /.row -->
                            <div class="row">
                              <div class="col-xs-12">
                                <ul class="tags">
                                  <li><a href="#">Electro</a></li>
                                  <li><a href="#">EDM</a></li>
                                  <li><a href="#">Trance</a></li>
                                </ul><!-- /.tags -->
                              </div><!-- /.col-xs-12 -->
                            </div><!-- /.row -->
                            <div class="buttons">
                              <div class="row">
                                <div class="col-xs-4">
                                  <div class="download">
                                    <a href="#"><img src="images/icon_download.svg" alt="Download icon" class="svg" /></a>
                                  </div><!-- /.download -->
                                </div><!-- /.col-xs-4 -->
                                <div class="col-xs-8">
                                  <div class="add pull-right">
                                    <a href="#"><img src="images/icon_plus-player.svg" alt="Plus player icon" class="svg" /></a>
                                  </div><!-- /.add -->
                                  <div class="love pull-right">
                                    <a href="#"><img src="images/icon_heart.svg" alt="Heart icon" class="svg" /> 158</a>
                                  </div><!-- /.love -->
                                </div><!-- /.col-xs-8 -->
                              </div><!-- /.row -->
                            </div><!-- /.buttons -->
                            <div class="clearfix"></div><!-- /.clearfix -->
                        </div><!-- /.assets -->
                      </div><!-- /.video-track -->
                    </div><!-- /.col-md-4 col-sm-height col-sm-top -->
                  </div><!-- /.row-height -->
                </div><!-- /.row -->
              </section><!-- /#banner-music-videos -->
            </div><!-- /.col-md-10 col-md-offset-1 -->
          </div><!-- /.row -->
        </div><!-- /.container -->
      </div><!-- /.banner -->


      <section id="music-videos">
        <h2 class="hide">Music videos page</h2><!-- /.hide -->
        <div class="container-fluid">
          <div class="row">
            <div class="col-xs-12">
              <div class="filter">
                <div class="clearfix"></div><!-- /.clearfix -->
                <nav class="navbar navbar-default">
                  <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
                      <span class="menu visible-xs">Menu</span>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                      <ul class="nav navbar-nav">
                        <span class="dropdown-title">Filter by:</span>
                        <div class="clearfix visible-xs"></div><!-- /.clearfix visible-xs -->
                        <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Genre <span class="caret"></span></a>
                          <ul class="dropdown-menu">
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                          </ul>
                        </li>
                        <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Mood <span class="caret"></span></a>
                          <ul class="dropdown-menu">
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                            <li><input type="checkbox" name="electronic" /><label for="electronic">Electronic</label></li>
                          </ul>
                        </li>
                      </ul>
                      <ul class="nav navbar-nav navbar-right">
                        <span class="dropdown-title">Sort by:</span>
                        <div class="clearfix visible-xs"></div><!-- /.clearfix visible-xs -->
                        <select name="sort" id="sort">
                          <option value="newest-to-oldest">Newest to oldest</option>
                          <option value="oldest-to-newest">Oldest to newest</option>
                        </select>
                      </ul>
                    </div><!-- /.navbar-collapse -->
                  </div><!-- /.container-fluid -->
                </nav>
              </div><!-- /.filter -->
            </div><!-- /.col-xs-12 -->
          </div><!-- /.row -->
          <div class="row">
            <div class="col-xs-12">
              <div class="filter-tags">
                <h2>Sub-genre</h2>
                <ul class="tags">
                  <li><a href="#">Eletro</a></li>
                  <li><a href="#">EDM</a></li>
                  <li><a href="#">Trance</a></li>
                  <li class="active"><a href="#">EDM</a></li>
                </ul><!-- /.tags -->
                <div class="clearfix"></div><!-- /.clearfix -->
              </div><!-- /.filter-tags -->
            </div><!-- /.col-xs-12 -->
          </div><!-- /.row -->
          <div class="row">
            <div class="col-xs-12">
              <div class="flexbox">
                <div class="video-track-constant">
                <img src="images/img_video-2.jpg" alt="" />
                  <div class="assets">
                    <div class="row">
                      <div class="col-xs-7">
                        <span class="author">James Ansell</span>
                      </div><!-- /.col-xs-7 -->
                      <div class="col-xs-5">
                        <span class="player-counter"><img src="images/icon_play.svg" alt="Play icon" /> 184</span>
                      </div><!-- /.col-xs-5 -->
                    </div><!-- /.row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h3>Track Name Here</h3>
                        </div><!-- /.col-xs-12 -->
                    </div><!-- /.row -->
                    <div class="row">
                      <div class="col-xs-12">
                        <ul class="tags">
                          <li><a href="#">Electro</a></li>
                          <li><a href="#">EDM</a></li>
                          <li><a href="#">Trance</a></li>
                        </ul><!-- /.tags -->
                      </div><!-- /.col-xs-12 -->
                    </div><!-- /.row -->
                    <div class="clearfix"></div><!-- /.clearfix -->
                    <div class="buttons">
                      <div class="row">
                        <div class="col-xs-4">
                          <div class="download">
                            <a href="#"><img src="images/icon_download.svg" alt="Download icon" class="svg" /></a>
                          </div><!-- /.download -->
                        </div><!-- /.col-xs-4 -->
                        <div class="col-xs-8">
                          <div class="add pull-right">
                            <a href="#"><img src="images/icon_plus-player.svg" alt="Plus player icon" class="svg" /></a>
                          </div><!-- /.add -->
                          <div class="love pull-right">
                            <a href="#"><img src="images/icon_heart.svg" alt="Heart icon" class="svg" /> 158</a>
                          </div><!-- /.love -->
                        </div><!-- /.col-xs-8 -->
                      </div><!-- /.row -->
                    </div><!-- /.buttons -->
                    <div class="clearfix"></div><!-- /.clearfix -->
                  </div><!-- /.assets -->
                </div><!-- /.video-track -->
                <div class="video-track-constant">
                  <img src="images/img_video-2.jpg" alt="" />
                  <div class="assets">
                    <div class="row">
                      <div class="col-xs-7">
                        <span class="author">James Ansell</span>
                      </div><!-- /.col-xs-7 -->
                      <div class="col-xs-5">
                        <span class="player-counter"><img src="images/icon_play.svg" alt="Play icon" /> 184</span>
                      </div><!-- /.col-xs-5 -->
                    </div><!-- /.row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h3>Track Name Here</h3>
                        </div><!-- /.col-xs-12 -->
                    </div><!-- /.row -->
                    <div class="row">
                      <div class="col-xs-12">
                        <ul class="tags">
                          <li><a href="#">Electro</a></li>
                          <li><a href="#">EDM</a></li>
                          <li><a href="#">Trance</a></li>
                        </ul><!-- /.tags -->
                      </div><!-- /.col-xs-12 -->
                    </div><!-- /.row -->
                    <div class="clearfix"></div><!-- /.clearfix -->
                    <div class="buttons">
                      <div class="row">
                        <div class="col-xs-4">
                          <div class="download">
                            <a href="#"><img src="images/icon_download.svg" alt="Download icon" class="svg" /></a>
                          </div><!-- /.download -->
                        </div><!-- /.col-xs-4 -->
                        <div class="col-xs-8">
                          <div class="add pull-right">
                            <a href="#"><img src="images/icon_plus-player.svg" alt="Plus player icon" class="svg" /></a>
                          </div><!-- /.add -->
                          <div class="love pull-right">
                            <a href="#"><img src="images/icon_heart.svg" alt="Heart icon" class="svg" /> 158</a>
                          </div><!-- /.love -->
                        </div><!-- /.col-xs-8 -->
                      </div><!-- /.row -->
                    </div><!-- /.buttons -->
                    <div class="clearfix"></div><!-- /.clearfix -->
                  </div><!-- /.assets -->
                </div><!-- /.video-track -->
                <div class="video-track-constant">
                  <img src="images/img_video-2.jpg" alt="" />
                  <div class="assets">
                    <div class="row">
                      <div class="col-xs-7">
                        <span class="author">James Ansell</span>
                      </div><!-- /.col-xs-7 -->
                      <div class="col-xs-5">
                        <span class="player-counter"><img src="images/icon_play.svg" alt="Play icon" /> 184</span>
                      </div><!-- /.col-xs-5 -->
                    </div><!-- /.row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h3>Track Name Here</h3>
                        </div><!-- /.col-xs-12 -->
                    </div><!-- /.row -->
                    <div class="row">
                      <div class="col-xs-12">
                        <ul class="tags">
                          <li><a href="#">Electro</a></li>
                          <li><a href="#">EDM</a></li>
                          <li><a href="#">Trance</a></li>
                        </ul><!-- /.tags -->
                      </div><!-- /.col-xs-12 -->
                    </div><!-- /.row -->
                    <div class="clearfix"></div><!-- /.clearfix -->
                    <div class="buttons">
                      <div class="row">
                        <div class="col-xs-4">
                          <div class="download">
                            <a href="#"><img src="images/icon_download.svg" alt="Download icon" class="svg" /></a>
                          </div><!-- /.download -->
                        </div><!-- /.col-xs-4 -->
                        <div class="col-xs-8">
                          <div class="add pull-right">
                            <a href="#"><img src="images/icon_plus-player.svg" alt="Plus player icon" class="svg" /></a>
                          </div><!-- /.add -->
                          <div class="love pull-right">
                            <a href="#"><img src="images/icon_heart.svg" alt="Heart icon" class="svg" /> 158</a>
                          </div><!-- /.love -->
                        </div><!-- /.col-xs-8 -->
                      </div><!-- /.row -->
                    </div><!-- /.buttons -->
                    <div class="clearfix"></div><!-- /.clearfix -->
                  </div><!-- /.assets -->
                </div><!-- /.video-track -->
                <div class="video-track-constant">
                  <img src="images/img_video-2.jpg" alt="" />
                  <div class="assets">
                    <div class="row">
                      <div class="col-xs-7">
                        <span class="author">James Ansell</span>
                      </div><!-- /.col-xs-7 -->
                      <div class="col-xs-5">
                        <span class="player-counter"><img src="images/icon_play.svg" alt="Play icon" /> 184</span>
                      </div><!-- /.col-xs-5 -->
                    </div><!-- /.row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h3>Track Name Here</h3>
                        </div><!-- /.col-xs-12 -->
                    </div><!-- /.row -->
                    <div class="row">
                      <div class="col-xs-12">
                        <ul class="tags">
                          <li><a href="#">Electro</a></li>
                          <li><a href="#">EDM</a></li>
                          <li><a href="#">Trance</a></li>
                        </ul><!-- /.tags -->
                      </div><!-- /.col-xs-12 -->
                    </div><!-- /.row -->
                    <div class="clearfix"></div><!-- /.clearfix -->
                    <div class="buttons">
                      <div class="row">
                        <div class="col-xs-4">
                          <div class="download">
                            <a href="#"><img src="images/icon_download.svg" alt="Download icon" class="svg" /></a>
                          </div><!-- /.download -->
                        </div><!-- /.col-xs-4 -->
                        <div class="col-xs-8">
                          <div class="add pull-right">
                            <a href="#"><img src="images/icon_plus-player.svg" alt="Plus player icon" class="svg" /></a>
                          </div><!-- /.add -->
                          <div class="love pull-right">
                            <a href="#"><img src="images/icon_heart.svg" alt="Heart icon" class="svg" /> 158</a>
                          </div><!-- /.love -->
                        </div><!-- /.col-xs-8 -->
                      </div><!-- /.row -->
                    </div><!-- /.buttons -->
                    <div class="clearfix"></div><!-- /.clearfix -->
                  </div><!-- /.assets -->
                </div><!-- /.video-track -->
                <div class="video-track-constant">
                  <img src="images/img_video-2.jpg" alt="" />
                  <div class="assets">
                    <div class="row">
                      <div class="col-xs-7">
                        <span class="author">James Ansell</span>
                      </div><!-- /.col-xs-7 -->
                      <div class="col-xs-5">
                        <span class="player-counter"><img src="images/icon_play.svg" alt="Play icon" /> 184</span>
                      </div><!-- /.col-xs-5 -->
                    </div><!-- /.row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h3>Track Name Here</h3>
                        </div><!-- /.col-xs-12 -->
                    </div><!-- /.row -->
                    <div class="row">
                      <div class="col-xs-12">
                        <ul class="tags">
                          <li><a href="#">Electro</a></li>
                          <li><a href="#">EDM</a></li>
                          <li><a href="#">Trance</a></li>
                        </ul><!-- /.tags -->
                      </div><!-- /.col-xs-12 -->
                    </div><!-- /.row -->
                    <div class="clearfix"></div><!-- /.clearfix -->
                    <div class="buttons">
                      <div class="row">
                        <div class="col-xs-4">
                          <div class="download">
                            <a href="#"><img src="images/icon_download.svg" alt="Download icon" class="svg" /></a>
                          </div><!-- /.download -->
                        </div><!-- /.col-xs-4 -->
                        <div class="col-xs-8">
                          <div class="add pull-right">
                            <a href="#"><img src="images/icon_plus-player.svg" alt="Plus player icon" class="svg" /></a>
                          </div><!-- /.add -->
                          <div class="love pull-right">
                            <a href="#"><img src="images/icon_heart.svg" alt="Heart icon" class="svg" /> 158</a>
                          </div><!-- /.love -->
                        </div><!-- /.col-xs-8 -->
                      </div><!-- /.row -->
                    </div><!-- /.buttons -->
                    <div class="clearfix"></div><!-- /.clearfix -->
                  </div><!-- /.assets -->
                </div><!-- /.video-track -->
                <div class="video-track-constant">
                  <img src="images/img_video-2.jpg" alt="" />
                  <div class="assets">
                    <div class="row">
                      <div class="col-xs-7">
                        <span class="author">James Ansell</span>
                      </div><!-- /.col-xs-7 -->
                      <div class="col-xs-5">
                        <span class="player-counter"><img src="images/icon_play.svg" alt="Play icon" /> 184</span>
                      </div><!-- /.col-xs-5 -->
                    </div><!-- /.row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h3>Track Name Here</h3>
                        </div><!-- /.col-xs-12 -->
                    </div><!-- /.row -->
                    <div class="row">
                      <div class="col-xs-12">
                        <ul class="tags">
                          <li><a href="#">Electro</a></li>
                          <li><a href="#">EDM</a></li>
                          <li><a href="#">Trance</a></li>
                        </ul><!-- /.tags -->
                      </div><!-- /.col-xs-12 -->
                    </div><!-- /.row -->
                    <div class="clearfix"></div><!-- /.clearfix -->
                    <div class="buttons">
                      <div class="row">
                        <div class="col-xs-4">
                          <div class="download">
                            <a href="#"><img src="images/icon_download.svg" alt="Download icon" class="svg" /></a>
                          </div><!-- /.download -->
                        </div><!-- /.col-xs-4 -->
                        <div class="col-xs-8">
                          <div class="add pull-right">
                            <a href="#"><img src="images/icon_plus-player.svg" alt="Plus player icon" class="svg" /></a>
                          </div><!-- /.add -->
                          <div class="love pull-right">
                            <a href="#"><img src="images/icon_heart.svg" alt="Heart icon" class="svg" /> 158</a>
                          </div><!-- /.love -->
                        </div><!-- /.col-xs-8 -->
                      </div><!-- /.row -->
                    </div><!-- /.buttons -->
                    <div class="clearfix"></div><!-- /.clearfix -->
                  </div><!-- /.assets -->
                </div><!-- /.video-track -->
                <div class="video-track-constant">
                  <img src="images/img_video-2.jpg" alt="" />
                  <div class="assets">
                    <div class="row">
                      <div class="col-xs-7">
                        <span class="author">James Ansell</span>
                      </div><!-- /.col-xs-7 -->
                      <div class="col-xs-5">
                        <span class="player-counter"><img src="images/icon_play.svg" alt="Play icon" /> 184</span>
                      </div><!-- /.col-xs-5 -->
                    </div><!-- /.row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h3>Track Name Here</h3>
                        </div><!-- /.col-xs-12 -->
                    </div><!-- /.row -->
                    <div class="row">
                      <div class="col-xs-12">
                        <ul class="tags">
                          <li><a href="#">Electro</a></li>
                          <li><a href="#">EDM</a></li>
                          <li><a href="#">Trance</a></li>
                        </ul><!-- /.tags -->
                      </div><!-- /.col-xs-12 -->
                    </div><!-- /.row -->
                    <div class="clearfix"></div><!-- /.clearfix -->
                    <div class="buttons">
                      <div class="row">
                        <div class="col-xs-4">
                          <div class="download">
                            <a href="#"><img src="images/icon_download.svg" alt="Download icon" class="svg" /></a>
                          </div><!-- /.download -->
                        </div><!-- /.col-xs-4 -->
                        <div class="col-xs-8">
                          <div class="add pull-right">
                            <a href="#"><img src="images/icon_plus-player.svg" alt="Plus player icon" class="svg" /></a>
                          </div><!-- /.add -->
                          <div class="love pull-right">
                            <a href="#"><img src="images/icon_heart.svg" alt="Heart icon" class="svg" /> 158</a>
                          </div><!-- /.love -->
                        </div><!-- /.col-xs-8 -->
                      </div><!-- /.row -->
                    </div><!-- /.buttons -->
                    <div class="clearfix"></div><!-- /.clearfix -->
                  </div><!-- /.assets -->
                </div><!-- /.video-track -->
                <div class="video-track-constant">
                  <img src="images/img_video-2.jpg" alt="" />
                  <div class="assets">
                    <div class="row">
                      <div class="col-xs-7">
                        <span class="author">James Ansell</span>
                      </div><!-- /.col-xs-7 -->
                      <div class="col-xs-5">
                        <span class="player-counter"><img src="images/icon_play.svg" alt="Play icon" /> 184</span>
                      </div><!-- /.col-xs-5 -->
                    </div><!-- /.row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h3>Track Name Here</h3>
                        </div><!-- /.col-xs-12 -->
                    </div><!-- /.row -->
                    <div class="row">
                      <div class="col-xs-12">
                        <ul class="tags">
                          <li><a href="#">Electro</a></li>
                          <li><a href="#">EDM</a></li>
                          <li><a href="#">Trance</a></li>
                        </ul><!-- /.tags -->
                      </div><!-- /.col-xs-12 -->
                    </div><!-- /.row -->
                    <div class="clearfix"></div><!-- /.clearfix -->
                    <div class="buttons">
                      <div class="row">
                        <div class="col-xs-4">
                          <div class="download">
                            <a href="#"><img src="images/icon_download.svg" alt="Download icon" class="svg" /></a>
                          </div><!-- /.download -->
                        </div><!-- /.col-xs-4 -->
                        <div class="col-xs-8">
                          <div class="add pull-right">
                            <a href="#"><img src="images/icon_plus-player.svg" alt="Plus player icon" class="svg" /></a>
                          </div><!-- /.add -->
                          <div class="love pull-right">
                            <a href="#"><img src="images/icon_heart.svg" alt="Heart icon" class="svg" /> 158</a>
                          </div><!-- /.love -->
                        </div><!-- /.col-xs-8 -->
                      </div><!-- /.row -->
                    </div><!-- /.buttons -->
                    <div class="clearfix"></div><!-- /.clearfix -->
                  </div><!-- /.assets -->
                </div><!-- /.video-track -->
                <div class="video-track-constant"></div><!-- /.video-track-constant -->
                <div class="video-track-constant"></div><!-- /.video-track-constant -->
                <div class="video-track-constant"></div><!-- /.video-track-constant -->
                <div class="video-track-constant"></div><!-- /.video-track-constant -->
                <div class="video-track-constant"></div><!-- /.video-track-constant -->
                <div class="video-track-constant"></div><!-- /.video-track-constant -->
                <div class="video-track-constant"></div><!-- /.video-track-constant -->
                <div class="video-track-constant"></div><!-- /.video-track-constant -->
              </div><!-- /.flexbox -->
            </div><!-- /.col-xs-12 -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </section><!-- /#dashboard -->

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
