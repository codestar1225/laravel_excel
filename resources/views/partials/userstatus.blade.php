@switch($status)
@case(1)
<span class="badge bg-green">Active</span>
@break
@case(2)
<span class="badge bg-red">Rejected</span>
@break
@case(3)
<span class="badge bg-gray">Closing</span>
@break
@case(4)
<span class="badge bg-gray">Closed</span>
@break
@default
<span class="badge bg-orange">Pending</span>
@endswitch