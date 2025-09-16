@extends('layouts.user.user_layout')

@section('content')
<div class="flex flex-col justify-center items-center min-h-screen bg-gray-100 px-4" >

    <!-- Card -->
    <div id="qrCard" class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-md text-center border-t-4 border-sky-500">
        
        <!-- Title -->
        <h2 class="text-2xl font-bold text-sky-600 mb-6" >Visitor QR Code</h2>

        <!-- QR Code -->
        <div class="flex justify-center mb-6" >
            <div class="bg-sky-50 p-5 rounded-xl border border-sky-200 shadow-inner inline-block">
                <div id="qrCode">
                    {!! QrCode::size(220)->style('square')->eye('circle')->generate($url) !!}
                </div>
            </div>
        </div>

        <!-- Info Text -->
        <p class="text-gray-600 font-medium">Scan this code to open the visitor entry form</p>
    </div>

    <!-- Buttons -->
    <div class="flex justify-center mt-6" >
        <button style="background-color:green;" onclick="downloadCard()" 
            class="bg-sky-500 hover:bg-sky-600 text-white font-semibold py-2 px-6 rounded-lg shadow transition">
            ⬇️ Download QR
        </button>
    </div>
</div>

<!-- html2canvas -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
function downloadCard() {
    const qrCard = document.getElementById("qrCard");
    html2canvas(qrCard, { scale: 2 }).then(canvas => {
        const link = document.createElement("a");
        link.download = "visitor_qr.png";
        link.href = canvas.toDataURL("image/png");
        link.click();
    });
}
</script>
@endsection
