<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Transaksi</h1>
                    </div>
                    <div class="col-sm-6">
                        <a :href="`${this.$props.onlineurl}`" class="btn btn-secondary float-right" :class="{'btn-success': type === 'online'}">Online</a>
                        <a :href="this.$props.offlineurl" class="btn btn-secondary float-right" :class="{'btn-success': type === 'offline'}">Offline</a>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">

            <div class="container-fluid" style="font-size: 14px;">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-12">
                        <div class="card">
                            <div class="card-header">Buat Transaksi</div>

                            <div class="card-body">
                                <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <input type="search"
                                                       v-model="searchKeyword"
                                                       @input="searchProduct"
                                                       class="form-control form-control-sm"
                                                       placeholder="Cari disini..."
                                                       aria-controls="table_barang">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div style="padding: 50px;display: flex;align-items: center;justify-content: center" v-if="loading">
                                    <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                                </div>

                                <div class="table-responsive" v-else>
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Kode Barang</th>
                                            <th>Nama</th>
                                            <th>Harga</th>
                                            <th>Stok</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="data in datas" :key="data.id">
                                                <td>{{ data.kode_barang }}</td>
                                                <td>{{ data.nama }}</td>
                                                <td>{{ data.harga_jual }}</td>
                                                <td>{{ data.stok }}</td>
                                                <td>
                                                    <button @click="addToCart(data)" class="btn btn-primary btn-sm" v-bind:disabled="data.stok == 0">Order</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="card">
                            <div class="card-header">Keranjang </div>
                            <div class="card-body">
                                <table class="table" id="t3">
                                    <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>jumlah</th>
                                        <th>Harga</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="row_cart" v-for="cart in cartData" :key="cart.id" style="cursor: pointer" @click="editCart(cart)">
                                            <td>{{ cart.nama }}</td>
                                            <td>{{ cart.qty }}</td>
                                            <td align="right">{{ cart.subtotal }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                    <td colspan="2" align="right">Total</td>
                                    <td colspan="2" align="right">{{ totalHarga }}</td>
                                    </tfoot>
                                </table>
                            </div>
                            <input type="submit" value="Jual" class="btn btn-block btn-primary" v-bind:disabled="totalHarga <= 0" @click="processCheckout">
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>

</template>
<script>
    export default {
        mounted() {
            this.axios.post(`${process.env.MIX_BASE_URL}/api/v2/transaksi/get-data/${this.$props.type}`, JSON.parse(this.$props.user))
                .then(res => {
                    this.datas = res.data
                    this.tempDatas = res.data
                    this.loading = false
                })

        },
        props: {
            type: String,
            offlineurl: String,
            onlineurl: String,
            user: {}
        },
        data() {
            return {
                loading: true,
                datas: [],
                tempDatas: [],
                cartData: [],
                searchKeyword: ""
            }
        },
        computed: {
            totalHarga() {
                return this.cartData.reduce((sum, item) => sum + item.subtotal, 0);
            }
        },
        methods : {
            searchProduct (e) {
                if (e.target.value === "") {
                    this.datas = this.tempDatas
                } else {
                    var search = new RegExp(e.target.value , 'i');
                    this.datas = this.tempDatas.filter(item => search.test(item.nama));
                }
            },
            editCart(data) {
                Vue.swal.fire({
                    title: '',
                    icon: 'question',
                    input: 'range',
                    inputLabel: data.nama,
                    inputAttributes: {
                        min: 1,
                        max: data.qty,
                        step: 1
                    },
                    inputValue: data.qty,
                    showCancelButton: true,
                    showDenyButton: true,
                    denyButtonText: "Hapus",
                    confirmButtonText: "Simpan",
                    cancelButtonText: "Kembali"
                }).then(result => {
                    if (result.isConfirmed) {
                        var qty = parseInt(result.value);
                        this.datas = this.datas.map(d => {
                            return d.id === data.id
                                ? {...d, stok: d.stok + (data.qty - qty)}
                                : {...d}
                        })

                        this.cartData = this.cartData.map(c => {
                            return c.id === data.id
                                ? {...c, qty: qty, subtotal: (qty * c.harga_jual)}
                                : {...c};
                        })

                    } else if (result.isDenied) {
                        if (window.confirm("Yakin ingin menghapus?")) {
                            this.cartData = this.cartData.filter(c => c.id !== data.id);

                            this.datas = this.datas.map(d => {
                                return d.id === data.id
                                ? {...d, stok: d.stok + data.qty}
                                : {...d}
                            })
                        }
                    }
                })
            },
            addToCart(data) {

                Vue.swal.fire({
                    title: '',
                    icon: 'question',
                    input: 'range',
                    inputLabel: data.nama,
                    inputAttributes: {
                        min: 1,
                        max: data.stok,
                        step: 1
                    },
                    inputValue: 1,
                    showCancelButton: true
                }).then(result => {
                    if (result.isConfirmed) {
                        var qty = parseInt(result.value);
                        this.datas = this.datas.map(d => {
                            return d.id === data.id
                            ? {...d, stok: d.stok - qty}
                            : {...d}
                        })

                        var findCart = this.cartData.filter(c => c.id === data.id);

                        if (findCart.length > 0) {
                            this.cartData = this.cartData.map(c => {
                                return c.id === data.id
                                    ? {...c, qty: (c.qty + qty), subtotal: c.subtotal + (qty * data.harga_jual)}
                                    : {...c};
                            })
                        } else {
                            this.cartData = [...this.cartData, {...data, qty, subtotal: (qty * data.harga_jual)}];
                        }

                    }
                })
            },
            processCheckout() {

                Vue.swal.fire({
                    title: 'Masukan nama pembeli',
                    input: 'text',
                    inputValue: "",
                    showCancelButton: true
                }).then(({isConfirmed, value}) => {
                    if (isConfirmed) {
                        var user = JSON.parse(this.$props.user)
                        this.axios.post(`${process.env.MIX_BASE_URL}/api/v2/transaksi/execution`, {
                            user_id: user.id,
                            type_trx: this.$props.type,
                            nama_pembeli: value,
                            data: this.cartData
                        }).then(res => {

                            this.cartData = [];
                            Vue.swal.fire({
                                icon: 'success',
                                title: 'Berhasil menyimpan data',
                                showConfirmButton: true,
                                timer: 3000
                            });
                        })
                    }
                })

            }
        }
    }
</script>
