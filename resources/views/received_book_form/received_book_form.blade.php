@extends('layout.layout')
@section('layout')
@if ($message = Session::get('success'))
<script>
    Swal.fire({
        icon: 'success'
        , title: '{{ $message }}'
    , })

</script>
@endif
<style>
    .container {
        margin-top: 20px;
    }

    #pdf-preview canvas {
        display: block;
        margin: 10px auto;
    }

</style>
<div class="container">
    <div class="row">
        <!-- ส่วนฟอร์มกรอกข้อมูล -->
        <div class="col-md-6">
            <form action="{{ route('store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- สมุดทะเบียน -->
                <div class="form-group mb-3 col-md-5 d-flex align-items-center">
                    <label for="register_type" class="col-md-5">สมุดทะเบียน :</label>
                    <select id="register_type" name="register_type" class="form-control" required>
                        <option value="รับทั่วไปปี 2568">รับทั่วไปปี 2568</option>
                        <option value="รับทั่วไป2">รับทั่วไป2</option>
                        <option value="รับทั่วไป">รับทั่วไป</option>
                        <option value="รับทั่วไป(กองช่าง)">รับทั่วไป(กองช่าง)</option>
                        <option value="สมุดทะเบียนรับ(ลับ)">สมุดทะเบียนรับ(ลับ)</option>
                    </select>
                </div>

                <!-- เลขที่หนังสือ -->
                <div class="form-group mb-3 col-md-8 d-flex align-items-center">
                    <label for="book_number" class="col-md-3">เลขที่หนังสือ :</label>
                    <div class="d-flex gap-2">
                        <input type="text" id="book_number" name="book_number" class="form-control" required> /
                        <input type="text" id="book_year" name="book_year" class="form-control" required>
                    </div>
                </div>

                <!-- ชั้นความเร็ว -->
                <div class="form-group mb-3 col-md-5 d-flex align-items-center">
                    <label for="urgency_level" class="col-md-4">ชั้นความเร็ว :</label>
                    <select id="urgency_level" name="urgency_level" class="form-control" required>
                        <option value="ด่วน">ด่วน</option>
                        <option value="ด่วนมาก">ด่วนมาก</option>
                        <option value="ด่วนที่สุด">ด่วนที่สุด</option>
                    </select>
                </div>

                @php
                use Carbon\Carbon;
                $today = Carbon::now()->format('Y-m-d'); // แปลงวันที่ปัจจุบันให้อยู่ในรูปแบบ yyyy-mm-dd
                @endphp

                <div class="row">
                    <!-- วันที่ได้รับ -->
                    <div class="form-group mb-3 col-md-4 d-flex align-items-center">
                        <label for="received_date" class="col-md-5">วันที่ได้รับ :</label>
                        <input type="date" id="received_date" name="received_date" class="form-control" value="{{ $today }}" min="{{ $today }}" max="{{ $today }}" required>
                    </div>
                    &nbsp;
                    &nbsp;
                    &nbsp;
                    <!-- ลงวันที่ -->
                    <div class="form-group mb-3 col-md-4 d-flex align-items-center">
                        <label for="registered_date" class="col-md-4">วันที่รับ :</label>
                        <input type="date" id="registered_date" name="registered_date" class="form-control" value="{{ $today }}" min="{{ $today }}" max="{{ $today }}">
                    </div>
                </div>

                <!-- เรื่อง -->
                <div class="form-group mb-3 col-md-10 d-flex align-items-center">
                    <label for="subject" class="col-md-2">เรื่อง :</label>
                    <textarea id="subject" name="subject" class="form-control" rows="2"></textarea>
                </div>

                <!-- เรียน -->
                <div class="form-group mb-3 col-md-10 d-flex align-items-center">
                    <label for="to_person" class="col-md-2">เรียน :</label>
                    <input type="text" id="to_person" name="to_person" class="form-control" required>
                </div>

                <!-- อ้างถึง -->
                <div class="form-group mb-3 col-md-10 d-flex align-items-center">
                    <label for="reference" class="col-md-2">อ้างถึง :</label>
                    <input type="text" id="reference" name="reference" class="form-control">
                </div>

                <!-- เนื้อหา -->
                <div class="form-group mb-3 col-md-10 d-flex align-items-center">
                    <label for="content" class="col-md-2">เนื้อหา :</label>
                    <textarea id="content" name="content" class="form-control" rows="2"></textarea>
                </div>

                <!-- หมายเหตุ -->
                <div class="form-group mb-3 col-md-10 d-flex align-items-center">
                    <label for="note" class="col-md-2">หมายเหตุ :</label>
                    <textarea id="note" name="note" class="form-control" rows="2"></textarea>
                </div>

                <!-- จาก -->
                <div class="form-group mb-3 col-md-10 d-flex align-items-center">
                    <label for="from_person" class="col-md-2">จาก :</label>
                    <input type="text" id="from_person" name="from_person" class="form-control" required>
                </div>

                <!-- แนบไฟล์ PDF -->
                <div class="form-group mb-3 col-md-10 d-flex align-items-center">
                    <label for="pdf_file" class="col-md-3">แนบไฟล์ PDF</label>
                    <input type="file" id="pdf_file" name="pdf_file[]" class="form-control" accept="application/pdf" multiple>
                </div>


                <!-- ปุ่มบันทึก -->
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>

        <!-- ส่วนแสดงตัวอย่างไฟล์ PDF -->
        <div class="col-md-6">
            <div class="mb-3">
                <label for="pdf-preview" class="form-label">ตัวอย่างไฟล์ PDF</label>
                <div id="pdf-preview" style="border: 1px solid #ccc; width: 794px; height: 1123px; overflow: auto; margin: 0 auto;">
                    <p class="text-center text-muted">กรุณาอัปโหลดไฟล์ PDF เพื่อแสดงตัวอย่าง</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<script>
    const pdfUpload = document.getElementById('pdf_file');
    const pdfPreview = document.getElementById('pdf-preview');

    pdfUpload.addEventListener('change', async (event) => {
        const file = event.target.files[0];

        if (file && file.type === 'application/pdf') {
            const fileURL = URL.createObjectURL(file);
            pdfPreview.innerHTML = ''; // ล้างข้อมูลเก่าใน preview

            const pdfjsLib = window['pdfjs-dist/build/pdf'];
            pdfjsLib.GlobalWorkerOptions.workerSrc =
                'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

            const pdf = await pdfjsLib.getDocument(fileURL).promise;

            for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                const page = await pdf.getPage(pageNum);
                const viewport = page.getViewport({
                    scale: 1
                });

                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.width = viewport.width;
                canvas.height = viewport.height;

                const renderContext = {
                    canvasContext: context
                    , viewport: viewport
                , };

                // Render the page into the canvas
                await page.render(renderContext).promise;

                // Append each rendered canvas (page) into the preview container
                pdfPreview.appendChild(canvas);
            }
        } else {
            pdfPreview.innerHTML = '<p class="text-center text-danger">กรุณาอัปโหลดไฟล์ PDF ที่ถูกต้อง</p>';
        }
    });

</script>
@endsection
