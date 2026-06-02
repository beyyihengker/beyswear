function editUser(user) {
    const modal = document.getElementById('userModal');
    const form = document.getElementById('userForm');

    document.getElementById('modalTitle').innerText = 'Edit User BeysWear';
    document.getElementById('userName').value = user.name;
    document.getElementById('userEmail').value = user.email;
    document.getElementById('userRole').value = user.role;

    // Mengubah form tambah user menjadi form edit user.
    // Method PUT dikirim melalui hidden input karena form HTML hanya mendukung GET/POST.
    form.action = `/users/${user.id}`;
    document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

    modal.style.display = 'block';
    window.scrollTo({ top: modal.offsetTop - 100, behavior: 'smooth' });
}

function toggleModal(id) {
    const modal = document.getElementById(id);

    // Menampilkan atau menyembunyikan modal berdasarkan kondisi display saat ini.
    if (modal.style.display === 'none' || modal.style.display === '') {
        modal.style.display = 'block';
    } else {
        modal.style.display = 'none';
    }
}

function closeModal() {
    document.getElementById('userModal').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function () {

    // Mengaktifkan hamburger menu pada tampilan mobile.
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');

    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function () {
            navMenu.classList.toggle('active');
        });
    }

    // Mengatur dropdown profil di navbar agar bisa dibuka/tutup.
    const navDropdown = document.getElementById('navDropdown');
    const dropdownToggle = document.querySelector('.nav-dropdown-toggle');

    if (navDropdown && dropdownToggle) {

        dropdownToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            navDropdown.classList.toggle('open');
        });

        const dropdownMenu = navDropdown.querySelector('.nav-dropdown-menu');

        if (dropdownMenu) {
            dropdownMenu.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        }

        // Menutup dropdown jika user klik area luar dropdown.
        document.addEventListener('click', function (e) {
            if (!navDropdown.contains(e.target)) {
                navDropdown.classList.remove('open');
            }
        });
    }

});

// Mengambil data produk fashion dari API eksternal untuk ditampilkan sebagai tren.
async function loadTrendFashion(){

    const container = document.getElementById('trendContainer');

    if(!container) return;

    container.innerHTML =
        `<div class="loading-box">Loading produk trend...</div>`;

    try{

        const response =
            await fetch('https://dummyjson.com/products?limit=100');

        const result = await response.json();

        // Kategori yang dianggap relevan dengan fashion.
        const allowedCategories = [
            'mens-shirts',
            'mens-shoes',
            'mens-watches',
            'womens-bags',
            'womens-dresses',
            'womens-jewellery',
            'womens-shoes',
            'womens-watches',
            'tops',
            'sunglasses',
            'beauty',
            'fragrances',
            'skin-care'
        ];

        const data = result.products.filter(item =>
            allowedCategories.includes(item.category)
        );

        // Mengambil 4 produk secara acak agar tampilan dashboard lebih dinamis.
        const randomProducts = data
            .sort(() => 0.5 - Math.random())
            .slice(0,4);

        let html = `<div class="trend-grid">`;

        randomProducts.forEach((item, index) => {
            html += `
                <div class="trend-card">
                    <div class="trend-img-wrap">
                        <span class="trend-rank">
                            ${String(index + 1).padStart(2,'0')}
                        </span>

                        <img src="${item.thumbnail}" alt="${item.title}">
                    </div>

                    <div class="trend-info">
                        <h4>${item.title}</h4>
                        <p>${item.category}</p>
                        <div class="trend-price">$${item.price}</div>
                    </div>
                </div>
            `;
        });

        html += `</div>`;

        container.innerHTML = html;

    }catch(error){

        container.innerHTML =
            `<div class="loading-box">Gagal mengambil data trend fashion.</div>`;
    }
}

loadTrendFashion();

// Live search produk menggunakan Fetch API.
// Data diambil dari endpoint /search-produk tanpa reload halaman.
const liveSearch =
    document.getElementById('liveSearch');

if(liveSearch){

    liveSearch.addEventListener(
        'keyup',

        async function(){

            const keyword = this.value;

            const response =
                await fetch(
                    `/search-produk?keyword=${keyword}`
                );

            const data =
                await response.json();

            let html = '';

            data.forEach(item => {

                html += `
                    <tr>

                        <td>${item.kode}</td>

                        <td>${item.nama}</td>

                        <td>${item.kategori}</td>

                        <td>${item.stok}</td>

                        <td>
                            Rp ${item.harga}
                        </td>

                    </tr>
                `;
            });

            document.getElementById(
                'dataProduk'
            ).innerHTML = html;
        }
    );
}

// Fungsi manual untuk menyimpan cookie dari sisi frontend.
function setCookie(name,value,days){

    let expires = "";

    if(days){

        const date = new Date();

        date.setTime(
            date.getTime() +
            (days*24*60*60*1000)
        );

        expires =
            "; expires=" +
            date.toUTCString();
    }

    document.cookie =
        name + "=" +
        value +
        expires +
        "; path=/";
}

// Mengambil nilai cookie berdasarkan nama cookie.
function getCookie(name){

    const nameEQ = name + "=";

    const ca =
        document.cookie.split(';');

    for(let i=0;i<ca.length;i++){

        let c = ca[i];

        while(c.charAt(0)==' '){

            c = c.substring(1);
        }

        if(c.indexOf(nameEQ)==0){

            return c.substring(
                nameEQ.length
            );
        }
    }

    return null;
}

// Menghapus cookie berdasarkan nama.
function deleteCookie(name){

    document.cookie =
        name +
        '=; Max-Age=-99999999;';
}

// Toggle dark mode cepat dari navbar.
const darkToggle = document.getElementById('darkToggle');

if(darkToggle){
    darkToggle.addEventListener('click', function(){
        document.documentElement.classList.toggle('dark');

        if(document.documentElement.classList.contains('dark')){
            setCookie('theme', 'dark', 7);
        }else{
            setCookie('theme', 'light', 7);
        }
    });
}

// Menyimpan preferensi tema dan ukuran font melalui endpoint Laravel.
const savePrefBtn = document.getElementById('savePref');

if (savePrefBtn) {
    savePrefBtn.addEventListener('click', async function () {
        const theme = document.getElementById('tema').value;
        const fontSize = document.getElementById('fontSize').value;

        const response = await fetch('/preferensi/save', {
            method: 'POST',

            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .content
            },

            body: JSON.stringify({
                theme: theme,
                font_size: fontSize
            })
        });

        const result = await response.json();

        alert(result.message);

        // Menerapkan tema langsung setelah preferensi disimpan.
        document.documentElement.classList.remove('dark');

        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        }

        if (theme === 'system') {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (prefersDark) {
                document.documentElement.classList.add('dark');
            }
        }

        // Menerapkan ukuran font langsung ke body.
        document.body.classList.remove(
            'font-small',
            'font-medium',
            'font-large'
        );

        document.body.classList.add('font-' + fontSize);
    });
}

// Elemen-elemen form transaksi.
const produkSelect = document.getElementById('produkSelect');
const ukuranSelect = document.getElementById('ukuranSelect');
const warnaSelect = document.getElementById('warnaSelect');
const stokInfo = document.getElementById('stokInfo');
const kodeInput = document.getElementById('kode');
const qtyInput = document.getElementById('qty');
const qtyError = document.getElementById('qtyError');
const btnTransaksi = document.getElementById('btnTransaksi');

// Menyimpan stok varian yang sedang dipilih.
// Nilai ini dipakai untuk validasi qty di sisi client.
let stokVarianAktif = 0;

if (produkSelect && ukuranSelect && warnaSelect) {

    produkSelect.addEventListener('change', function () {
        ukuranSelect.innerHTML = '<option value="">Pilih Ukuran</option>';
        warnaSelect.innerHTML = '<option value="">Pilih Warna</option>';

        stokInfo.textContent = '';
        qtyError.textContent = '';
        qtyInput.value = '';
        qtyInput.removeAttribute('max');
        stokVarianAktif = 0;

        // window.produkData berasal dari Blade penjualan.
        // Data ini berisi produk beserta variannya.
        const produk = window.produkData.find(p => p.id == this.value);

        if (!produk) {
            kodeInput.value = '';
            return;
        }

        kodeInput.value = produk.kode;

        const ukuranUnik = [];
        let totalStok = 0;

        // Mengisi dropdown ukuran berdasarkan varian yang masih memiliki stok.
        produk.varians.forEach(v => {
            totalStok += Number(v.stok);

            if (v.ukuran && v.stok > 0 && !ukuranUnik.includes(v.ukuran)) {
                ukuranUnik.push(v.ukuran);
                ukuranSelect.innerHTML += `<option value="${v.ukuran}">${v.ukuran}</option>`;
            }
        });

        // Mendukung produk yang tidak memiliki ukuran,
        // tetapi tetap memiliki pilihan warna.
        const warnaTanpaUkuran = produk.varians.filter(v =>
            !v.ukuran && v.warna && v.stok > 0
        );

        warnaTanpaUkuran.forEach(v => {
            warnaSelect.innerHTML += `
                <option value="${v.warna}" data-stok="${v.stok}">
                    ${v.warna} | Stok: ${v.stok}
                </option>
            `;
        });

        stokInfo.textContent = `Total stok tersedia: ${totalStok}`;
    });

    ukuranSelect.addEventListener('change', function () {
        warnaSelect.innerHTML = '<option value="">Pilih Warna</option>';
        qtyError.textContent = '';
        qtyInput.value = '';
        qtyInput.removeAttribute('max');
        stokVarianAktif = 0;

        const produk = window.produkData.find(p => p.id == produkSelect.value);

        if (!produk) return;

        // Setelah ukuran dipilih, dropdown warna hanya menampilkan warna
        // yang cocok dengan ukuran tersebut dan stoknya masih tersedia.
        produk.varians.forEach(v => {
            if (v.ukuran == this.value && v.stok > 0) {
                warnaSelect.innerHTML += `
                    <option value="${v.warna}" data-stok="${v.stok}">
                        ${v.warna || '-'} | Stok: ${v.stok}
                    </option>
                `;
            }
        });
    });

    warnaSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];

        // Menyimpan stok varian aktif dari data-stok pada option warna.
        stokVarianAktif = Number(selectedOption.dataset.stok || 0);

        if (stokVarianAktif > 0) {
            qtyInput.max = stokVarianAktif;
            stokInfo.textContent = `Stok varian tersedia: ${stokVarianAktif}`;
        }
    });

    qtyInput.addEventListener('input', function () {
        const qty = Number(this.value);

        // Validasi qty agar tidak melebihi stok varian.
        if (stokVarianAktif > 0 && qty > stokVarianAktif) {
            qtyError.textContent = `Qty melebihi stok. Stok tersedia hanya ${stokVarianAktif}.`;
            btnTransaksi.disabled = true;
        } else {
            qtyError.textContent = '';
            btnTransaksi.disabled = false;
        }
    });
}

function toggleEdit(id)
{
    const row = document.getElementById('edit-row-' + id);

    // Menampilkan atau menyembunyikan form edit user
    // yang berada pada baris tabel yang sama.
    if (
        row.style.display === 'none' ||
        row.style.display === ''
    ) {
        row.style.display = 'table-row';
    } else {
        row.style.display = 'none';
    }
}

function toggleEditProfil() {
    const box = document.getElementById('editProfilBox');

    // Toggle form edit profil tanpa pindah halaman.
    box.style.display =
        box.style.display === 'none' || box.style.display === ''
            ? 'block'
            : 'none';

    // Saat form dibuka, halaman otomatis scroll ke form
    // agar user langsung melihat area yang sedang diedit.
    if (box.style.display === 'block') {
        box.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Keranjang transaksi sementara di sisi client.
// Data baru dikirim ke server saat form disimpan.
let cart = [];

const btnAddCart = document.getElementById('btnAddCart');
const cartTable = document.getElementById('cartTable');
const cartInputs = document.getElementById('cartInputs');

if (btnAddCart) {
    btnAddCart.addEventListener('click', function () {

        const produkId = produkSelect.value;
        const ukuran = ukuranSelect.value;
        const warna = warnaSelect.value;
        const qty = Number(qtyInput.value);

        const produk = window.produkData.find(p => p.id == produkId);

        if (!produk) {
            alert('Pilih produk dulu.');
            return;
        }

        // Minimal salah satu atribut varian harus dipilih.
        if (!ukuran && !warna) {
            alert('Pilih ukuran atau warna.');
            return;
        }

        if (!qty || qty < 1) {
            alert('Qty wajib diisi.');
            return;
        }

        if (stokVarianAktif > 0 && qty > stokVarianAktif) {
            alert(`Qty melebihi stok. Stok tersedia ${stokVarianAktif}.`);
            return;
        }

        // Subtotal dihitung di client untuk kebutuhan tampilan.
        // Perhitungan final tetap dilakukan kembali di server.
        const subtotal = Number(produk.harga) * qty;

        cart.push({
            produk_id: produkId,
            produk: produk.nama,
            ukuran: ukuran,
            warna: warna,
            qty: qty,
            harga: Number(produk.harga),
            subtotal: subtotal
        });

        renderCart();

        // Reset form setelah item berhasil dimasukkan ke keranjang.
        produkSelect.value = '';
        ukuranSelect.innerHTML = '<option value="">Pilih Ukuran</option>';
        warnaSelect.innerHTML = '<option value="">Pilih Warna</option>';
        qtyInput.value = '';
        kodeInput.value = '';
        stokInfo.textContent = '';
    });
}

function renderCart() {

    // Tidak menjalankan proses jika elemen keranjang tidak ditemukan.
    if (!cartTable || !cartInputs) return;

    // Menampilkan placeholder jika keranjang kosong.
    if (cart.length === 0) {
        cartTable.innerHTML = `
            <tr>
                <td colspan="7" style="text-align:center;">Belum ada item.</td>
            </tr>
        `;

        cartInputs.innerHTML = '';
        return;
    }

    let tableHtml = '';
    let inputHtml = '';

    cart.forEach((item, index) => {

        // Tampilan tabel untuk user.
        tableHtml += `
            <tr>
                <td>${item.produk}</td>
                <td>${item.ukuran || '-'}</td>
                <td>${item.warna || '-'}</td>
                <td>${item.qty}</td>
                <td>Rp ${item.harga.toLocaleString('id-ID')}</td>
                <td>Rp ${item.subtotal.toLocaleString('id-ID')}</td>
                <td class="aksi-btn">
                    <button type="button" class="btn btn-primer" style="background:#c0392b;" onclick="removeCartItem(${index})">
                        Hapus
                    </button>
                </td>
            </tr>
        `;

        // Hidden input digunakan agar seluruh isi keranjang
        // tetap terkirim saat form transaksi disubmit.
        inputHtml += `
            <input type="hidden" name="items[${index}][produk_id]" value="${item.produk_id}">
            <input type="hidden" name="items[${index}][ukuran]" value="${item.ukuran}">
            <input type="hidden" name="items[${index}][warna]" value="${item.warna}">
            <input type="hidden" name="items[${index}][qty]" value="${item.qty}">
        `;
    });

    cartTable.innerHTML = tableHtml;
    cartInputs.innerHTML = inputHtml;
}

// Menghapus item dari keranjang berdasarkan index array.
function removeCartItem(index) {
    cart.splice(index, 1);
    renderCart();
}

window.addEventListener('DOMContentLoaded', async function () {

    try {

        // Mengambil preferensi pengguna yang sebelumnya
        // disimpan melalui cookie dan endpoint Laravel.
        const response = await fetch('/preferensi/get');

        const result = await response.json();

        const theme = result.theme || 'light';
        const fontSize = result.font_size || 'medium';

        // Menerapkan tema yang tersimpan.
        document.documentElement.classList.remove('dark');

        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        }

        if (theme === 'system') {

            const prefersDark =
                window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (prefersDark) {
                document.documentElement.classList.add('dark');
            }
        }

        // Menerapkan ukuran font yang tersimpan.
        document.body.classList.remove(
            'font-small',
            'font-medium',
            'font-large'
        );

        document.body.classList.add('font-' + fontSize);

        // Sinkronisasi nilai select dengan preferensi yang aktif.
        const tema = document.getElementById('tema');
        const font = document.getElementById('fontSize');

        if (tema) {
            tema.value = theme;
        }

        if (font) {
            font.value = fontSize;
        }

    } catch (error) {

        // Tidak menampilkan alert agar UI tetap nyaman
        // jika preferensi gagal dimuat.
        console.log('Gagal load preferensi');
    }
});

const fontToggle =
    document.getElementById('fontToggle');

if(fontToggle){

    const fontLevels = [
        'small',
        'medium',
        'large'
    ];

    fontToggle.addEventListener(
        'click',

        async function(){

            // Mengambil ukuran font saat ini dari cookie
            // lalu berpindah ke ukuran berikutnya.
            let current =
                getCookie('font_size') || 'medium';

            let index =
                fontLevels.indexOf(current);

            index++;

            if(index >= fontLevels.length){
                index = 0;
            }

            const nextFont =
                fontLevels[index];

            // Terapkan ukuran font baru ke halaman.
            document.body.classList.remove(
                'font-small',
                'font-medium',
                'font-large'
            );

            document.body.classList.add(
                'font-' + nextFont
            );

            // Simpan ke cookie agar tetap berlaku
            // pada kunjungan berikutnya.
            setCookie(
                'font_size',
                nextFont,
                7
            );

            // Sinkronisasi preferensi ke server.
            await fetch(
                '/preferensi/save',
                {
                    method:'POST',

                    headers:{
                        'Content-Type':'application/json',

                        'X-CSRF-TOKEN':
                            document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content
                    },

                    body:JSON.stringify({
                        theme:
                            getCookie('theme') || 'light',

                        font_size:
                            nextFont
                    })
                }
            );
        }
    );
}