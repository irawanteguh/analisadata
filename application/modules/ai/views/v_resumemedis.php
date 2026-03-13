<style>
.position-relative {
    position: relative;
}

#aiOverlay {
    display: none;
    position: absolute;
    inset: 0;
    background: rgba(255,255,255,0.92);
    z-index: 10;
    border-radius: 6px;
}

.ai-icon {
    font-size: 90px;
    color: #0d6efd;
}

/* Animation class (baru aktif kalau ditambahkan) */
.animate-ai {
    animation: zoomRotateAI 2s infinite ease-in-out;
}

@keyframes zoomRotateAI {
    0% {
        transform: scale(1) rotate(0deg);
        opacity: 0.6;
    }
    50% {
        transform: scale(1.25) rotate(180deg);
        opacity: 1;
    }
    100% {
        transform: scale(1) rotate(360deg);
        opacity: 0.6;
    }
}
</style>


<a href="javascript:void(0)" 
   id="btnGenerateAI"
   class="btn btn-primary"
   onclick="generateAI('123')">
   <i class="bi bi-stars"></i> Generate Resume AI
</a>

<br><br>

<div class="position-relative">
    
    <div id="aiOverlay" 
         class="d-flex flex-column justify-content-center align-items-center text-center">
        <i class="bi bi-stars ai-icon"></i>
        <div class="mt-3 fw-bold">AI sedang menyusun resume medis...</div>
    </div>

    <textarea class="form-control"
              name="hasil_resume"
              id="hasil_resume"
              rows="20"></textarea>
</div>