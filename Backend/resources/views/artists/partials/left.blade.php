<div class="row">
    <div class="col-md-2 user-profile">
        <h3 class="text-center"><% currentUser.first_name %> <% currentUser.last_name %></h3>
        <button class="btn btn-danger btn-block">FOLLOW ME</button>
        <hr>
        <ul class="list-unstyled text-center">
            <li><p><i class="fa fa-link m-r-xs"></i><a href="#"><% currentUser.email %></a></p></li>
            <li><p><i class="fa fa-map-marker m-r-xs"></i><% currentUser.country %>, <% currentUser.city %></p></li>
            <li><p><i class="fa fa-envelope m-r-xs"></i><a href="#">example@mail.com</a></p></li>
        </ul>
        <hr>
        
    </div>
