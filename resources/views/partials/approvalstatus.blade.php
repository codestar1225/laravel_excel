@switch($status)
@case(1)
<span class="badge bg-green">Approved</span>
@break
@case(2)
<span class="badge bg-gray">Rejected</span>
@break
@default
<span class="badge bg-orange">Pending</span>
@endswitch
