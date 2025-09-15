<tbody wire:poll.5s>

            @forelse($livevisitors as $visitor)
                <tr>
                    <td class="text-center">{{ $visitor->getCustomer->name }}</td>
                    <td class="text-center">{{ $visitor->count }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($visitor->in_time)->format('h:i A') }}</td>
                    @php
                        $endTime = \Carbon\Carbon::parse($visitor->end_time);
                        $now = \Carbon\Carbon::now('Asia/Kolkata');
                        $diffInMinutes = $now->diffInMinutes($endTime, false); // false → keeps negative values
                    @endphp
                    <td class="text-center {{ $diffInMinutes <= 10 ? 'text-danger fw-bold' : '' }}">{{ \Carbon\Carbon::parse($visitor->end_time)->format('h:i A') }} - {{$diffInMinutes}}</td>
                    <td class="text-center">{{ $visitor->total_amount }}</td>
                    <td class="text-center">{{ $visitor->floaty_number }}</td>
                    <td class="text-center">{{ $visitor->floaty_advance }}</td>
                    <td class="text-center">
                    @if ($visitor->floaty_status==1)
                    <a href="#" wire:click="markFloatyReturned({{ $visitor->id }})"><span class="badge text-bg-warning">Pending</span></a>
                    @else
                    <span class="badge text-bg-primary">N/A</span>
                    @endif
                    </td>
                    <td class="text-center">{{ number_format($visitor->total_amount - $visitor->paid_amount, 2) }}</td>
                    <td class="text-center">
                        @if ($visitor->total_amount > $visitor->paid_amount)
                            <a href="#" @if ($visitor->rent_item_count > 0) onclick="if(confirm('Before exit confirm all rent items are returned ?')) { @this.exitWithPayment({{ $visitor->id }}, 'Cash', {{ $visitor->total_amount }}) }" @else wire:click="exitWithPayment({{ $visitor->id }},'Cash',{{$visitor->total_amount}})" @endif class="btn btn-success btn-sm">Cash</a>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="#" wire:click="exitWithPayment({{ $visitor->id }},'G pay',{{$visitor->total_amount}})" class="btn btn-success btn-sm">G pay</a>
                        @else
                            <a href="#" wire:click="markExit({{ $visitor->id }})" class="btn btn-danger px-5 btn-sm">Exit</a>
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($visitor->rent_item_count > 0)
                            <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="getRentItems({{$visitor->id}});"><span class="badge text-bg-warning">Pending {{ $visitor->rent_item_count }}</span></a>
                        @else
                            <span class="badge text-bg-primary">N/A</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">No records found</td>
                </tr>
            @endforelse

</tbody>
