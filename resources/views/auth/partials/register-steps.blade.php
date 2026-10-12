{{-- Pengatur langkah pendaftaran: pindah langkah, validasi per langkah, indikator progres, dan ringkasan di langkah terakhir. --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('regForm');
        const steps = [...form.querySelectorAll('.step-content')];
        const bars = [...document.querySelectorAll('[data-step-bar]')];
        const titles = JSON.parse(form.dataset.titles);
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const stepNumber = document.getElementById('stepNumber');
        const stepTitle = document.getElementById('stepTitle');
        const total = steps.length;
        // Mulai dari langkah yang punya kesalahan setelah server menolak data.
        let current = Math.min(Math.max(Number(form.dataset.start) || 1, 1), total);

        const isStepValid = () => {
            for (const field of steps[current - 1].querySelectorAll('input, select')) {
                if (!field.checkValidity()) {
                    field.reportValidity();
                    return false;
                }
            }
            return true;
        };

        const fillSummary = () => {
            const value = (name) => (form.querySelector(`[name="${name}"]`)?.value || '').trim();
            const name = value('name');
            const initials = name.split(/\s+/).filter(Boolean).slice(0, 2).map((part) => part[0]).join('').toUpperCase() || '?';
            const set = (id, text) => {
                const el = document.getElementById(id);
                if (el) el.textContent = text || '-';
            };

            set('confirm-avatar', initials);
            set('confirm-name', name);
            set('confirm-subinfo', `Sekolah: ${value('school_name')} • Kelas ${value('class')}`);
            set('confirm-email', value('email'));
            set('confirm-whatsapp', value('whatsapp'));
        };

        const render = () => {
            steps.forEach((el, i) => el.classList.toggle('hidden', i + 1 !== current));
            bars.forEach((bar, i) => {
                bar.classList.toggle('bg-primary-container', i < current);
                bar.classList.toggle('bg-surface-container-high', i >= current);
            });
            stepNumber.textContent = current;
            stepTitle.textContent = titles[current - 1];
            prevBtn.classList.toggle('invisible', current === 1);

            const last = current === total;
            nextBtn.querySelector('[data-next-label]').textContent = last ? 'Kirim pendaftaran' : 'Lanjut';
            nextBtn.querySelector('[data-next-icon]').classList.toggle('hidden', last);
            nextBtn.querySelector('[data-send-icon]').classList.toggle('hidden', !last);

            if (last) fillSummary();
        };

        nextBtn.addEventListener('click', () => {
            if (current < total) {
                if (!isStepValid()) return;
                current++;
                render();
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            nextBtn.disabled = true;
            nextBtn.querySelector('[data-next-label]').textContent = 'Mengirim...';
            form.requestSubmit();
        });

        prevBtn.addEventListener('click', () => {
            if (current > 1) {
                current--;
                render();
            }
        });

        document.querySelectorAll('[data-skip-step]').forEach((btn) => {
            btn.addEventListener('click', () => nextBtn.click());
        });

        // Kekuatan kata sandi: hanya panduan visual, aturan sebenarnya ada di server.
        const password = document.getElementById('password');
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');
        password.addEventListener('input', () => {
            const length = password.value.length;
            const [width, color, label] = length === 0
                ? ['0%', 'bg-surface-container-high', 'Minimal 8 karakter']
                : length < 4
                    ? ['25%', 'bg-error', 'Kekuatan: Lemah']
                    : length < 8
                        ? ['60%', 'bg-warning', 'Kekuatan: Sedang']
                        : ['100%', 'bg-primary-container', 'Kekuatan: Kuat'];
            strengthBar.style.width = width;
            strengthBar.className = `h-full rounded-full transition-all duration-500 ${color}`;
            strengthText.textContent = label;
        });

        render();
    });
</script>
