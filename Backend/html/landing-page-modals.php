<?php include "header.php"; ?>

	<body class="landing-page-modals">
		<!--[if lt IE 10]>
	      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	    <![endif]-->

	    <div class="animsition">

	    	<div class="background-blur-image"></div><!-- /.background-blur-image -->

	    	<!-- Navigation -->
	        <nav class="navbar navbar-default wow fadeInDown" data-wow-duration="1s" role="navigation">
	            <h2 class="hide">Main navigation</h2><!-- /.hide -->
	            <div class="container">
	                <div class="navbar-header page-scroll">
	                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
	                        <span class="sr-only">Toggle navigation</span>
	                        <span class="icon-bar"></span>
	                        <span class="icon-bar"></span>
	                        <span class="icon-bar"></span>
	                    </button>
	                    <a class="page-scroll" href="#page-top"><img src="images/stm_logo.png" alt="Sore Thumb Media Throbbing with talent" /></a>
	                </div>

	                <!-- Collect the nav links, forms, and other content for toggling -->
	                <div class="collapse navbar-collapse navbar-ex1-collapse">
	                    <ul class="nav navbar-nav">
	                        <li>
	                            <a href="#">Join us</a>
	                        </li>
	                    </ul>
	                </div>
	                <!-- /.navbar-collapse -->
	            </div>
	            <!-- /.container -->
	        </nav>
	        <div class="container">
	        	<div class="row">
	        		<div class="col-xs-12">
	        			<!-- Button trigger modal -->
						<a class="red-button" onClick="$('#joinUsUserModal').modal()">Join Us User</a>

						<!-- Modal -->
						<div class="modal fade" id="joinUsUserModal" tabindex="-1" role="dialog" aria-labelledby="joinUsUserModalLabel">
						  	<div class="modal-dialog" role="document">
							    <div class="modal-content">
							      	<div class="modal-header">
								        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
								        <h2 class="modal-title" id="joinUsUserModalLabel">Join Us Today</h2>
							      	</div>
							      	<div class="modal-body">
							        	<a href="#" class="sign-up-facebook"><i class="fa fa-facebook"></i> <span>Sign up with Facebook</span></a>

							        	<h3>Or Sign Up With Email Below</h3>
							        	<form action="#">
							        		<select name="account-type" id="accountType">
							        			<option value="" disabled selected hidden>Select Account Type</option>
							        			<option value="author">Artist</option>
							        			<option value="user">User</option>
							        		</select>
							        		<input type="text" placeholder="Profile Name" />
							        		<input type="email" placeholder="Email" />
							        		<input type="password" placeholder="Password" />
							        		<input type="password" placeholder="Confirm Password" />
							        		<input type="submit" value="Sign up now" />
							        	</form>

							        	<span class="have-an-account">Already have an account? <a href="#">Login</a></span>

							        	<span class="footer">
							        		We will never post to Facebook without your permission. By creating an account, you agree to our <a href="#" class="animsition-link">Terms</a> and <a href="#" class="animsition-link">Privacy Policy</a>.
							        	</span>
							      	</div>
							    </div>
						 	</div>
						</div>
	        		</div><!-- /.col-xs-12 -->
	        	</div><!-- /.row -->
	        	<div class="row">
	        		<div class="col-xs-12">
	        			<!-- Button trigger modal -->
						<a class="red-button" onClick="$('#joinUsArtistModal').modal()">Join Us Artist</a>

						<!-- Modal -->
						<div class="modal fade" id="joinUsArtistModal" tabindex="-1" role="dialog" aria-labelledby="joinUsArtistModalLabel">
						  	<div class="modal-dialog" role="document">
							    <div class="modal-content">
							      	<div class="modal-header">
								        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
								        <h2 class="modal-title" id="joinUsArtistModalLabel">Join Us Today</h2>
							      	</div>
							      	<div class="modal-body">
							        	<a href="#" class="sign-up-facebook"><i class="fa fa-facebook"></i> <span>Sign up with Facebook</span></a>

							        	<h3>Or Sign Up With Email Below</h3>
							        	<form action="#">
							        		<select name="account-type" id="accountType">
							        			<option value="" disabled selected hidden>Select Account Type</option>
							        			<option value="author">Artist</option>
							        			<option value="user">User</option>
							        		</select>
							        		<input type="text" placeholder="Artist Name" />
							        		<input type="email" placeholder="Email" />
							        		<input type="password" placeholder="Password" />
							        		<input type="password" placeholder="Confirm Password" />

							        		<a href="#" class="connect-soundcloud"><i class="fa fa-soundcloud"></i> <span>Connect to SoundCloud</span></a>

							        		<h3>Select A Track</h3>
							        		<p>Sore Thumb Media is a quality controlled platform. For you to obtain an artist profile we will need to hear your sounds first. Please select your best track to date from the list below. You will be notified if you are accepted as an artist. Until then, check out what we have to offer across the site!</p>
							        		<p class="note">Please note: If your demo is not accepted, you may not submit a track to us for 2 months.</p>

							        		<div class="radio-toolbar">
											    <input type="radio" id="radio1" name="radios" value="all" checked>
											    <label for="radio1">This is first track name</label>

											    <input type="radio" id="radio2" name="radios"value="false">
											    <label for="radio2">Second track name</label>

											    <input type="radio" id="radio3" name="radios" value="true">
											    <label for="radio3">Third track</label>
											</div>

											<p>PLEASE BE AWARE</p>
											<p>- Artists with 20,000+ Soundcloud followers will not be able to use the platform until the next phase of Sore Thumb Media has been introduced. That being said, you will still be eligible to use our gating services.</p>
											<p>- Even though we are a multi-genre music platform, we currently do not accept Hip-Hop, Rap, R&B or Acoustic</p>

							        		<input type="submit" value="Sign up now" />
							        	</form>

							        	<span class="have-an-account">Already have an account? <a href="#">Login</a></span>

							        	<span class="footer">
							        		We will never post to Facebook without your permission. By creating an account, you agree to our <a href="#" class="animsition-link">Terms</a> and <a href="#" class="animsition-link">Privacy Policy</a>.
							        	</span>
							      	</div>
							    </div>
						 	</div>
						</div>
	        		</div><!-- /.col-xs-12 -->
	        	</div><!-- /.row -->
	        	<div class="row">
	        		<div class="col-xs-12">
	        			<!-- Button trigger modal -->
						<a class="red-button" onClick="$('#submitYourDemoModal').modal()">Submit Your Demo</a>

						<!-- Modal -->
						<div class="modal fade" id="submitYourDemoModal" tabindex="-1" role="dialog" aria-labelledby="submitYourDemoModalLabel">
						  	<div class="modal-dialog" role="document">
							    <div class="modal-content">
							      	<div class="modal-header">
								        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
								        <h2 class="modal-title" id="submitYourDemoModalLabel">Submit Your Demo</h2>
							      	</div>
							      	<div class="modal-body">
							      		<h3>Are you submitting a track for</h3>

							      		<div>
									  		<ul class="nav nav-tabs reset-ul" role="tablist">
											    <li role="presentation" class="active"><a href="#discover" aria-controls="discover" role="tab" data-toggle="tab">Discover</a></li>
											    <li role="presentation"><a href="#music-video" aria-controls="music-video" role="tab" data-toggle="tab">Music video</a></li>
										  	</ul>

										  	<!-- Tab panes -->
										  	<div class="tab-content">
											    <div role="tabpanel" class="tab-pane fade in active" id="discover">
													<p>Discover carries no signature sound. We aim to cover the whole spectrum of electronic music here, ranging from commercial right through to underground!</p>
													<h3>Number of submissions remaining this month</h3>
													<span class="number-counter">4</span>

													<h3>Select demo to submit</h3>
													<p>Please be aware, although we do accept the majority of tracks submitted to Discover, we may not feature tracks which:</p>
													<ul>
														<li>Are poorly mixed / mastered</li>
														<li>Do not meet our requirements in terms of creativity</li>
														<li>Have poor quality and / or unprofessional artwork</li>
													</ul>

													<form action="#">
														<select name="account-type" id="accountType">
										        			<option value="" disabled selected hidden>Demo track name here</option>
										        			<option value="author">Demo track name here</option>
										        			<option value="user">Demo track name here</option>
										        		</select>

														<div class="checkbox-wrapper">
															<div class="checkbox-input">
																<input type="checkbox" value="None" id="disover-criteria" name="check" />
																<label class="label-asset" for="disover-criteria"></label>
															</div><!-- /.checkbox-input -->
															<label class="checkbox-label" for="disover-criteria">
											        			Please tick this box to confirm that the above criteria has been followed and understood
											        		</label>
														</div><!-- /.checkbox-wrapper -->

														<div class="checkbox-wrapper">
															<div class="checkbox-input">
																<input type="checkbox" value="None" id="discover-terms-and-conditions" name="check" />
																<label class="label-asset" for="discover-terms-and-conditions"></label>
															</div><!-- /.checkbox-input -->
															<label class="checkbox-label" for="discover-terms-and-conditions">
											        			I have read and accepted the <a href="#">terms and conditions</a>
											        		</label>
														</div><!-- /.checkbox-wrapper -->
														<div class="checkbox-wrapper">
															<div class="checkbox-input">
																<input type="checkbox" value="None" id="music-video-accept" name="check" />
																<label class="label-asset" for="music-video-accept"></label>
															</div><!-- /.checkbox-input -->
															<label class="checkbox-label" for="music-video-accept">
																I accept my track may not feature in discover
															</label>
														</div><!-- /.checkbox-wrapper -->
														<input type="submit" value="Submit track" />
													</form>
											    </div>
											    <div role="tabpanel" class="tab-pane fade" id="music-video">
													<p>We endeavour to create music video's for tracks which are likeable across a wide range of listeners. Therefore, our signature sound is melodic with feel-good vibes which include catchy melodies, preferably accompanied by original vocals / vocal stabs. </p>
													<p>However, for artists that prefer more of an underground vibe, we will consider darker tracks providing they include a melodic twist. Please study our music video's to get a better understanding of our signature!</p>

													<h3>Number of submissions remaining this month</h3>
													<span class="number-counter">2</span>

													<h3>Select demo to submit</h3>
													<p>Before you send your demo, your track must:</p>
													<ul>
														<li>Be private on Soundcloud (with download enabled)</li>
														<li>Not have been circulated, sent to any other record labels, promotional channels etc.</li>
														<li>Be 100% original (do not use bootleg vocals, use royalty free samples only etc)</li>
													</ul>

													<form action="#">
														<select name="account-type" id="accountType">
										        			<option value="" disabled selected hidden>Demo track name here</option>
										        			<option value="author">Demo track name here</option>
										        			<option value="user">Demo track name here</option>
										        		</select>

														<div class="checkbox-wrapper">
															<div class="checkbox-input">
																<input type="checkbox" value="None" id="music-video-terms-and-conditions" name="check" />
																<label class="label-asset" for="music-video-terms-and-conditions"></label>
															</div><!-- /.checkbox-input -->
															<label class="checkbox-label" for="music-video-terms-and-conditions">
											        			I have read and accepted the <a href="#">terms and conditions</a>
											        		</label>
														</div><!-- /.checkbox-wrapper -->

														<div class="checkbox-wrapper">
															<div class="checkbox-input">
																<input type="checkbox" value="None" id="music-video-criteria" name="check" />
																<label class="label-asset" for="music-video-criteria"></label>
															</div><!-- /.checkbox-input -->
															<label class="checkbox-label" for="music-video-criteria">
											        			Please tick this box to confirm that the above criteria has been followed and understood
											        		</label>
														</div><!-- /.checkbox-wrapper -->


														<input type="submit" value="Submit track" />
													</form>
											    </div>
										  	</div>
										</div>
							      	</div>
							    </div>
						 	</div>
						</div>
	        		</div><!-- /.col-xs-12 -->
	        	</div><!-- /.row -->
	        	<div class="row">
	        		<div class="col-xs-12">
	        			<!-- Button trigger modal -->
						<a class="red-button" onClick="$('#signInModal').modal()">Sign In</a>

						<!-- Modal -->
						<div class="modal fade" id="signInModal" tabindex="-1" role="dialog" aria-labelledby="signInModalLabel">
						  	<div class="modal-dialog" role="document">
							    <div class="modal-content">
							      	<div class="modal-header">
								        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
								        <h2 class="modal-title" id="signInModalLabel">Sign In</h2>
							      	</div>
							      	<div class="modal-body">
							        	<a href="#" class="sign-up-facebook"><i class="fa fa-facebook"></i> <span>Sign in with Facebook</span></a>

							        	<h3>Or Sign In With Email Below</h3>
							        	<form action="#">
							        		<input type="email" placeholder="Email" />
							        		<input type="password" placeholder="Password" />
							        		<input type="submit" value="Sign in" />

							        		<a href="#" class="forgotten-password">Forgotten password</a>
							        	</form>

							        	<span class="have-an-account">Don't have an account? <a href="#">Login</a></span>
							      	</div>
							    </div>
						 	</div>
						</div>
	        		</div><!-- /.col-xs-12 -->
	        	</div><!-- /.row -->
	        	<div class="row">
	        		<div class="col-xs-12">
	        			<!-- Button trigger modal -->
						<a class="red-button" onClick="$('#submitYourRemixModal').modal()">Submit Your Remix</a>

						<!-- Modal -->
						<div class="modal fade" id="submitYourRemixModal" tabindex="-1" role="dialog" aria-labelledby="submitYourRemixModalLabel">
						  	<div class="modal-dialog" role="document">
							    <div class="modal-content">
							      	<div class="modal-header">
								        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
								        <h2 class="modal-title" id="submitYourRemixModalLabel">Submit Your Remix</h2>
							      	</div>
							      	<div class="modal-body">
							        	<h3>Number of submissions remaining for this competition</h3>

							        	<span class="number-counter">1</span>

							        	<h3>Before you send your remix, your track must:</h3>
							        	<ul>
							        		<li>Be private on Soundcloud (with download enabled).</li>
							        		<li>Not have been circulated, sent to any other record labels, promotional channels etc.</li>
							        		<li>Include samples only from the stem pack and 100% royalty free samples</li>
							        		<li>Not be uploaded to Soundcloud until the winners have been announced. Otherwise, you will be disqualified from the competition</li>
							        	</ul>

							        	<h3>Select remix to submit</h3>

							        	<form action="#">
							        		<select name="account-type" id="accountType">
							        			<option value="" disabled selected hidden>Demo track name here</option>
							        			<option value="author">Demo track name here</option>
							        			<option value="user">Demo track name here</option>
							        		</select>

											<div class="checkbox-wrapper">
												<div class="checkbox-input">
													<input type="checkbox" value="None" id="submit-remixterms-and-conditions" name="check" />
													<label class="label-asset" for="submit-remixterms-and-conditions"></label>
												</div><!-- /.checkbox-input -->
												<label class="checkbox-label" for="submit-remixterms-and-conditions">
								        			I have read and accepted the <a href="#">terms and conditions</a>
								        		</label>
											</div><!-- /.checkbox-wrapper -->

							        		<input type="submit" value="Submit remix" />
							        	</form>
							      	</div>
							    </div>
						 	</div>
						</div>
	        		</div><!-- /.col-xs-12 -->
	        	</div><!-- /.row -->
	        	<div class="row">
	        		<div class="col-xs-12">
	        			<!-- Button trigger modal -->
						<a class="red-button" onClick="$('#downloadRemixStemsModal').modal()">Download Remix Stems</a>

						<!-- Modal -->
						<div class="modal fade" id="downloadRemixStemsModal" tabindex="-1" role="dialog" aria-labelledby="downloadRemixStemsModalLabel">
						  	<div class="modal-dialog" role="document">
							    <div class="modal-content">
							      	<div class="modal-header">
								        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
								        <h2 class="modal-title" id="downloadRemixStemsModalLabel">Download Remix Stems</h2>
							      	</div>
							      	<div class="modal-body">
							        	<h3>Competition Rules</h3>

							        	<p>The stems provided are for remixing the original track. Please do not use these stems in your own production and claim them as your own (unless submitting the track to this competition). This is a breach of our terms and conditions.</p>

							        	<ul>
							        		<li>All remixes must be uploaded to Soundcloud but kept private with downloading enabled.</li>
							        		<li>Once you have created your remix, do not circulate this through any promotional channels, record labels etc. until the competition winners have been announced</li>
							        		<li>Credit all artists involved in creating this remix</li>
							        	</ul>

							        	<h3>Competition Prizes</h3>

							        	<ul class="competition-prizes-list reset-ul">
							        		<li><strong>1st Place:</strong> This is the prize</li>
							        		<li><strong>2nd Place:</strong> This is the prize</li>
							        		<li><strong>3rd Place:</strong> This is the prize</li>
							        	</ul><!-- /.competition-prizes-list -->

							        	<p>If you have understood these rules, download the stems using the link below. Good luck!</p>

							        	<a href="#" class="download-stems">Download stems</a>
							      	</div>
							    </div>
						 	</div>
						</div>
	        		</div><!-- /.col-xs-12 -->
	        	</div><!-- /.row -->
	        	<div class="row">
	        		<div class="col-xs-12">
	        			<!-- Button trigger modal -->
						<a class="red-button" onClick="$('#addToPlaylistModal1').modal()">Add To Playlist 1</a>

						<!-- Modal -->
						<div class="modal fade" id="addToPlaylistModal1" tabindex="-1" role="dialog" aria-labelledby="addToPlaylistModal1Label">
						  	<div class="modal-dialog" role="document">
							    <div class="modal-content">
							      	<div class="modal-header">
								        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
								        <h2 class="modal-title" id="addToPlaylistModal1Label">Add To Playlist</h2>
							      	</div>
							      	<div class="modal-body">
							        	<h3>Want to follow this artist? Just click below</h3>

							        	<a href="#" class="sign-up-facebook"><i class="fa fa-facebook"></i> <span>Sign up with Facebook</span></a>

							        	<a href="#" class="connect-soundcloud"><i class="fa fa-soundcloud"></i> <span>Connect to SoundCloud</span></a>

							        	<p><a href="#">No thanks, just add to playlist</a></p>
							      	</div>
							    </div>
						 	</div>
						</div>
	        		</div><!-- /.col-xs-12 -->
	        	</div><!-- /.row -->
	        	<div class="row">
	        		<div class="col-xs-12">
	        			<!-- Button trigger modal -->
						<a class="red-button" onClick="$('#addToPlaylistModal2').modal()">Add To Playlist 2</a>

						<!-- Modal -->
						<div class="modal fade" id="addToPlaylistModal2" tabindex="-1" role="dialog" aria-labelledby="addToPlaylistModal2Label">
						  	<div class="modal-dialog" role="document">
							    <div class="modal-content">
							      	<div class="modal-header">
								        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
								        <h2 class="modal-title" id="addToPlaylistModal2Label">Add To Playlist</h2>
							      	</div>
							      	<div class="modal-body">
							        	<h3>Want to existing playlist or create a new one</h3>

							        	<form action="#">
							        		<select name="account-type" id="accountType">
							        			<option value="" disabled selected hidden>Select playlist</option>
							        			<option value="author">Select playlist</option>
							        			<option value="user">Select playlist</option>
							        		</select>

											<input type="text" required placeholder="Create playlist" />

							        		<input type="submit" value="Create playlist" />
							        	</form>
							      	</div>
							    </div>
						 	</div>
						</div>
	        		</div><!-- /.col-xs-12 -->
	        	</div><!-- /.row -->
	        	<div class="row">
	        		<div class="col-xs-12">
	        			<!-- Button trigger modal -->
						<a class="red-button" onClick="$('#createPlaylistModal').modal()">Create Playlist</a>

						<!-- Modal -->
						<div class="modal fade" id="createPlaylistModal" tabindex="-1" role="dialog" aria-labelledby="createPlaylistModalLabel">
						  	<div class="modal-dialog" role="document">
							    <div class="modal-content">
							      	<div class="modal-header">
								        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
								        <h2 class="modal-title" id="createPlaylistModalLabel">Create Playlist</h2>
							      	</div>
							      	<div class="modal-body">
							        	<h3>Add to exisiting playlist or create a new</h3>

							        	<form action="#">
							        		<select name="account-type" id="accountType">
							        			<option value="" disabled selected hidden>Select playlist</option>
							        			<option value="author">Select playlist</option>
							        			<option value="user">Select playlist</option>
							        		</select>

											<input type="text" required placeholder="Create playlist" />

							        		<input type="submit" value="Create playlist" />
							        	</form>
							      	</div>
							    </div>
						 	</div>
						</div>
	        		</div><!-- /.col-xs-12 -->
	        	</div><!-- /.row -->
	        </div><!-- /.container -->
	    </div><!-- /.animsition -->
	</body><!-- /.landing-page-music -->

<?php include "footer.php"; ?>