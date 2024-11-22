@extends('layout.layout')

@section('layout')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.9.179/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


<div class="container">
    @php
    $pdfFile = $receivedbook->BookFile->first()->file_path ?? null;
    $pdfPath = $pdfFile ? asset('storage/' . $pdfFile) : null;
    $fileExists = $pdfFile ? file_exists(storage_path('app/public/' . $pdfFile)) : false;
    @endphp

    <div>
        <button class="btn btn-primary" id="stamp-btn">แสตม</button>
        <button class="btn btn-primary" id="download-btn">ดาวน์โหลด PDF</button>

    </div>

    <br>

    @if ($pdfPath)
    <div style="text-align: center;">
        <canvas id="pdf-canvas" style="border: 1px solid #ccc; width: auto; height: auto; overflow: auto; margin: 0 auto;"></canvas>
        <div class="text-center mt-3">
            <button id="prev-page" class="btn btn-secondary">หน้าก่อนหน้า</button>
            <span id="page-info">หน้าที่ <span id="page-num">1</span> จาก <span id="page-count">1</span></span>
            <button id="next-page" class="btn btn-secondary">หน้าถัดไป</button>
        </div>
    </div>

    <script>
        const pdfUrl = "{{ $pdfPath }}";
        const stampImageUrl = "{{ asset('stamp_png/book4.png') }}"; // เส้นทางของภาพ PNG ที่จะใช้เป็นแสตมป์

        let pdfDoc = null
            , pageNum = 1
            , pageRendering = false
            , pageNumPending = null
            , scale = 1.5
            , canvas = document.getElementById('pdf-canvas')
            , ctx = canvas.getContext('2d')
            , stampImg = new Image()
            , stampPos = {
                x: 100
                , y: 100
                , width: 100
                , height: 100
            }; // ตำแหน่งเริ่มต้นของแสตมป์

        // โหลด PDF
        pdfjsLib.getDocument(pdfUrl).promise.then((pdfDoc_) => {
            pdfDoc = pdfDoc_;
            document.getElementById('page-count').textContent = pdfDoc.numPages;
            renderPage(pageNum); // แสดงหน้าที่ 1
        }).catch((error) => {
            console.error("Error loading PDF:", error);
        });

        function renderPage(num) {
            pageRendering = true;
            pdfDoc.getPage(num).then((page) => {
                const viewport = page.getViewport({
                    scale: scale
                });
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                const renderContext = {
                    canvasContext: ctx
                    , viewport: viewport
                };

                // เรียก render เพียงครั้งเดียว
                const renderTask = page.render(renderContext);

                renderTask.promise.then(() => {
                    pageRendering = false;

                    // ถ้ามีหน้ารอให้ render
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }

                    // วาดแสตมป์หลังจาก render หน้าเสร็จ
                    refreshStamp();
                });
            });

            document.getElementById('page-num').textContent = num;
        }

        function refreshStamp() {
            // วาดหน้า PDF ใหม่
            pdfDoc.getPage(pageNum).then((page) => {
                const viewport = page.getViewport({
                    scale: scale
                });
                const renderContext = {
                    canvasContext: ctx
                    , viewport: viewport
                };

                page.render(renderContext).promise.then(() => {
                    // วาดแสตมป์ใหม่หลังจากแสดง PDF เสร็จ
                    ctx.globalCompositeOperation = "source-over"; // ป้องกันการลบพื้นหลัง
                    if (stampImg.complete && stampImg.naturalWidth > 0) {
                        ctx.drawImage(stampImg, stampPos.x, stampPos.y, stampImg.width, stampImg.height);

                        // วาดวันที่และเวลา
                        drawStampWithDateTime();
                    }
                });
            });
        }


        let isStampPlaced = false; // ตัวแปรบ่งชี้ว่าแสตมป์ถูกวางแล้วหรือยัง
        let stampDateTime = null; // ตัวแปรเก็บวันที่และเวลาที่ล็อค

        function clearStamp() {
            // ลบเฉพาะพื้นที่ของแสตมป์
            ctx.clearRect(stampPos.x, stampPos.y, stampImg.width, stampImg.height);
            refreshStamp(); // วาดหน้า PDF ใหม่หลังล้างแสตมป์
        }

        function drawStampWithDateTime() {
            // วาดภาพแสตมป์
            ctx.drawImage(stampImg, stampPos.x, stampPos.y, stampImg.width, stampImg.height);

            // ถ้าแสตมป์ถูกวางแล้ว (หยุดขยับ) และยังไม่ได้เก็บวันที่และเวลา
            if (!isStampPlaced) {
                const now = new Date();
                stampDateTime = now; // เก็บวันที่และเวลาเมื่อแสตมป์ถูกวาง
                isStampPlaced = true; // ตั้งสถานะว่าแสตมป์ถูกวางแล้ว
            }

            // ตรวจสอบว่ามีวันที่และเวลาหรือยัง
            if (stampDateTime) {
                const dateText = stampDateTime.toLocaleDateString(); // เฉพาะวันที่
                const timeText = stampDateTime.toLocaleTimeString(); // เฉพาะเวลา

                // กำหนดสไตล์ข้อความ
                ctx.font = '20px Arial'; // ปรับขนาดและฟอนต์ข้อความ
                ctx.fillStyle = 'rgba(0, 0, 255, 0.8)'; // สีน้ำเงินโปร่งใส 80%
                ctx.textAlign = 'center'; // จัดข้อความให้อยู่ตรงกลาง

                // วาดข้อความ "stamptest"
                const testText = "อบต. smart city";
                ctx.fillText(
                    testText
                    , stampPos.x + stampImg.width / 2, // ตำแหน่ง x (ตรงกลางของแสตมป์)
                    stampPos.y - -35 // ตำแหน่ง y (เหนือแสตมป์)
                );

                // วาดข้อความวันที่
                ctx.fillText(
                    dateText
                    , stampPos.x + stampImg.width / 2, // ตำแหน่ง x (ตรงกลางของแสตมป์)
                    stampPos.y + stampImg.height + -55 // ตำแหน่ง y (ใต้แสตมป์เล็กน้อย)
                );

                // วาดข้อความเวลา (เว้นบรรทัดจากวันที่)
                ctx.fillText(
                    timeText
                    , stampPos.x + stampImg.width / 2, // ตำแหน่ง x (ตรงกลางของแสตมป์)
                    stampPos.y + stampImg.height + -20 // ตำแหน่ง y (เว้นบรรทัดจากวันที่)
                );
            }
        }



        // ฟังก์ชั่นรีเซ็ตการวางแสตมป์
        function resetStampPosition() {
            // เมื่อเริ่มขยับแสตมป์ใหม่ ให้รีเซ็ตตัวแปร
            isStampPlaced = false;
            stampDateTime = null;
        }

        // ฟังก์ชันวาดแสตมป์
        function drawStamp(rotation) {
            // ล้างการแปลงก่อนหน้านี้
            ctx.setTransform(1, 0, 0, 1, 0, 0);

            // ตั้งค่าการหมุนให้ตรงกับ PDF
            if (rotation === 90) {
                ctx.translate(canvas.width, 0);
                ctx.rotate((Math.PI / 180) * 90);
            } else if (rotation === 180) {
                ctx.translate(canvas.width, canvas.height);
                ctx.rotate((Math.PI / 180) * 180);
            } else if (rotation === 270) {
                ctx.translate(0, canvas.height);
                ctx.rotate((Math.PI / 180) * 270);
            }

            // วาดแสตมป์ในตำแหน่งที่ต้องการ
            ctx.drawImage(stampImg, stampPos.x, stampPos.y, stampPos.width, stampPos.height);

            // รีเซ็ตการหมุน
            ctx.setTransform(1, 0, 0, 1, 0, 0);
        }


        // ฟังก์ชันเปลี่ยนหน้า
        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        // ปุ่ม "หน้าก่อนหน้า"
        document.getElementById('prev-page').addEventListener('click', () => {
            if (pageNum <= 1) return;
            pageNum--;
            queueRenderPage(pageNum);
        });

        // ปุ่ม "หน้าถัดไป"
        document.getElementById('next-page').addEventListener('click', () => {
            if (pageNum >= pdfDoc.numPages) return;
            pageNum++;
            queueRenderPage(pageNum);
        });

        document.getElementById('stamp-btn').addEventListener('click', () => {
            stampImg.src = stampImageUrl;

            stampImg.onload = () => {
                refreshStamp();
            };
        });

        let isDragging = false; // ตัวแปรบ่งชี้ว่าแสตมป์กำลังถูกลากหรือไม่
        let startX, startY; // ตัวแปรบันทึกตำแหน่งเริ่มต้นของการลาก

        canvas.addEventListener('mousedown', (e) => {
            const mouseX = e.offsetX;
            const mouseY = e.offsetY;

            if (mouseX >= stampPos.x && mouseX <= stampPos.x + stampPos.width &&
                mouseY >= stampPos.y && mouseY <= stampPos.y + stampPos.height) {
                isDragging = true;
            }
        });

        canvas.addEventListener('mousemove', (e) => {
            if (isDragging) {
                const mouseX = e.offsetX;
                const mouseY = e.offsetY;

                stampPos.x = mouseX - stampPos.width / 2;
                stampPos.y = mouseY - stampPos.height / 2;

                // อัปเดตเฉพาะแสตมป์
                refreshStamp();
            }
        });

        canvas.addEventListener('mouseup', () => {
            isDragging = false;

            // ลบแสตมป์ก่อนหน้านี้
            clearStamp();

            // เพิ่มแสตมป์ใหม่พร้อมวันที่และเวลา
            drawStampWithDateTime();
        });

    </script>
    @endif

    <script>
        document.getElementById('download-btn').addEventListener('click', () => {
            const {
                jsPDF
            } = window.jspdf;

            const pdf = new jsPDF({
                orientation: 'portrait', // แนวตั้ง
                unit: 'mm', // ใช้หน่วยเป็นมิลลิเมตร
                format: 'a4' // กำหนดขนาดหน้ากระดาษ A4
            });

            // รับข้อมูลจาก canvas
            const imgData = canvas.toDataURL('image/png');

            // ขนาดของหน้ากระดาษ A4
            const pdfWidth = 210; // ความกว้าง A4 (มม.)
            const pdfHeight = 297; // ความสูง A4 (มม.)

            // คำนวณขนาดของภาพให้พอดีกับ A4
            const canvasWidth = canvas.width;
            const canvasHeight = canvas.height;

            const ratio = Math.min(pdfWidth / canvasWidth, pdfHeight / canvasHeight);
            const imgWidth = canvasWidth * ratio;
            const imgHeight = canvasHeight * ratio;

            // เพิ่มภาพจาก canvas ลงใน PDF
            pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);

            // ดาวน์โหลด PDF
            pdf.save('document_with_stamp.pdf');
        });

    </script>
</div>
@endsection
