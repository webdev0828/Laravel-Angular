{!! Form::hidden('id', 'idate(format)', array('id' => 'id')) !!}
<div class="panel panel-default st-panel">
    <div class="panel-body">
      <div class="row form-group">
         <div class="col-md-6">
            <label>First Name</label>
            {!! Form::text('first_name', null, ['id'=>'first_name', 'class'=>'form-control','placeholder'=>'First Name', 'required']) !!}
        </div>
         <div class="col-md-6">
            <label>Last Name</label>
            {!! Form::text('last_name', null, ['id'=>'last_name', 'class'=>'form-control','placeholder'=>'Last Name', 'required']) !!}
         </div>
      </div>
      <div class="form-group">
         <label>Email Id</label>
         {!! Form::text('email', null, ['id'=>'email', 'class'=>'form-control','placeholder'=>'Email', 'required']) !!}
      </div>
      <div class="form-group">
         <label>Artist Name</label>
         {!! Form::text('name', null, ['id'=>'name', 'class'=>'form-control','placeholder'=>'Artist Name', 'required']) !!}
      </div>
      <div class="row form-group">
         <div class="col-md-6 selectParent">
            <label>Country</label>
            {!! Form::select('country', [
               '' => 'Please select',
               'India' => 'India',
               'USA' => 'USA']
            ,null,['class'=>'form-control']) !!}
         </div>
         <div class="col-md-6 selectParent">
            <label>Town/City</label>
            {!! Form::select('city', [
               '' => 'Please select',
               'Pune' => 'Pune',
               'Mumbai' => 'Mumbai']
            ,null,['class'=>'form-control']) !!}
         </div>
      </div>
      <div class="form-group">
         <label>Website</label>
         {!! Form::text('website', null, ['id'=>'website', 'class'=>'form-control','placeholder'=>'Website']) !!}
      </div>
      <div class="form-group">
         <label>Genre (You can pick up 3)</label>
         <div class="row">
            @foreach ($genres as $genre)
                <label class="form-group col-md-3">
                    <input type="checkbox" class="form-control">{!! $genre->name !!}
                </label>
            @endforeach
          </div>  
      </div>
      <div class="form-group">
         <label>Password</label>
         {!! Form::password('password', ['class' => 'form-control']) !!}
      </div>
      <div class="form-group">
         <label>Confirm Password</label>
         {!! Form::password('password', ['class' => 'form-control']) !!}
      </div>
   </div>
</div> 