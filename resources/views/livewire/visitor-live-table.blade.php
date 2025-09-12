<tbody wire:poll.5s>

            @forelse($livevisitors as $visitor)
                <tr>
                    <td class="text-center">{{ $visitor->getCustomer->name }}</td>
                    <td class="text-center">{{ $visitor->count }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($visitor->in_time)->format('h:i A') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($visitor->end_time)->format('h:i A') }}</td>
                    <td class="text-center">{{ $visitor->total_amount }}</td>
                    <td class="text-center">{{ $visitor->floaty_number }}</td>
                    <td class="text-center">{{ $visitor->floaty_advance }}</td>
                    <td class="text-center">
                    @if ($visitor->floaty_status==1)
                    <a href=""><span class="badge text-bg-warning">Pending</span></a>
                    @else
                    <span class="badge text-bg-primary">N/A</span>
                    @endif
                    </td>
                    <td class="text-center">
                        @if ($visitor->total_amount > $visitor->paid_amount)
                            <a href="" class="btn btn-success btn-sm">Cash</a>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="" class="btn btn-success btn-sm">G pay</a>
                        @else
                            <a href="" class="btn btn-danger px-5 btn-sm">Exit</a>
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
