window.onload = function () 
{
    toastr.options.timeOut = 3000;

    Vue.filter('formatDate', function(value) {
        if (value) {
          return moment(String(value)).format('DD/MM/YY')
        }
    });

    Vue.filter('formatTime', function(value) {
        if (value) {
          return moment(String(value)).format('hh:mm')
        }
    });

    Vue.config.devtools = true
    // Vue.js
    if (document.getElementById('main')) 
    {
        new Vue({
            el: '#main',
            delimiters: ['!{', '}!'],
            created: function () {
                this.getSubOrdenes();
                this.getClientes();
                this.getFdPago();
            },
            data: {
                clientes: [],
                clienteElegido: 2,
                fsdpago: [],
                fdpagoElegida: 1,
                newCant: 1,
                newCod: null,
                descuento: null,
                total: null,
                subOrdenes: [],
                pagoCon: null,
                pagoEfec: 0
            },
            methods:{
                getClientes: function(){
                    var urlClientes = '/admin/getclientes';
                    axios.get(urlClientes).then(response =>{
                        this.clientes = response.data
                    });
                },
                getFdPago: function(){
                    var urlFdPago = '/admin/getfdpago';
                    axios.get(urlFdPago).then(response =>{
                        this.fsdpago = response.data
                    });
                },
                getSubOrdenes: function(){
                    var urlSubOrdenes = '/admin/getsubordenes/' + App.id_order;
                    axios.get(urlSubOrdenes).then(response =>{
                        this.subOrdenes = response.data;
                        this.setFocus();
                    });
                },
                createSuborden: function(){
                    var urlcreatesuborden = '/admin/createsuborden/' + App.id_order;
                    axios.post(urlcreatesuborden, {
                        cantidad: this.newCant,
                        codigo: this.newCod
                      }).then(response =>{
                        this.getSubOrdenes();
                        this.newCant = 1;
                        this.newCod = null;
                        toastr.success(response.data.message);
                    }).catch(function (error) {
                        toastr.error(error.response.data.message, error.response.data.titulo);
                    });
                },
                deleteSuborden: function(subOrden){
                    var urldeletesuborden = '/admin/deletesuborden/' + subOrden.id;
                    axios.delete(urldeletesuborden)
                    .then(response =>{
                        this.getSubOrdenes();
                        toastr.error(response.data.message);
                    });
                },
                setFocus: function() {
                    // Note, you need to add a ref="search" attribute to your input.
                    if (this.$refs.codigo) {
                        this.$refs.codigo.focus();
                    }
                },
                resetPagocon: function() {
                    this.pagoCon = null;
                    this.pagoEfec = 0;
                },
                resetPagoEfec: function() {
                    this.pagoEfec = null;
                },
                resetPagoTarj: function() {
                    this.pagoEfecTarj = 0;
                },
                resetDesc: function() {
                    this.descuento = null;
                }
            },
            computed: {
                totalSuma: function() {
                    if (this.fdpagoElegida != 1) {
                        this.descuento = null;
                    }
                    
                    let total = 0;
                    for(let i = 0; i < this.subOrdenes.length; i++)
                    {
                        total += Math.ceil(this.subOrdenes[i].cantidad * this.subOrdenes[i].monto);
                    }

                    if (this.descuento > total) {
                        this.descuento = total;
                    }
                    
                    return total - this.descuento;
                },
                vuelto: function() {
                    vuelto = this.pagoCon - this.totalSuma;
                    if (vuelto < 0) {
                        vuelto = 0;
                    }
                    return vuelto;
                },
                fiado: function() {
                    if(this.pagoCon > 0)
                    {
                        fiado = this.totalSuma - this.pagoCon;
                        if (fiado < 0) {
                            fiado = 0;
                        }
                        return fiado;
                    }
                },
                aPagar: function() {
                    if (this.pagoCon <= this.totalSuma) {
                        aPagar = this.pagoCon;
                    } else {
                        aPagar = this.totalSuma;
                    }
                    
                    return aPagar;
                },
                pagoEfecTarj: {
                    get: function() {
                        if (this.aPagar >= this.pagoEfec) {
                            return this.aPagar - this.pagoEfec;
                        }
                        else{
                            this.pagoEfecTarj = 0;
                            return 0;
                        }
                    },
                    set: function(newValue) {
                        this.pagoEfec = this.aPagar - newValue;
                        
                        if (this.pagoEfec < 0) {
                            this.pagoEfec = 0;
                        }
                    }
                }
            }
        });
    }
    // Vue.js
    if (document.getElementById("productos")) 
    {
        function debounce (fn, delay) {
            var timeoutID = null
            return function () {
                clearTimeout(timeoutID)
                var args = arguments
                var that = this
                timeoutID = setTimeout(function () {
                    fn.apply(that, args)
                }, delay)
            }
        }

        new Vue({
            el: '#productos',
            delimiters: ['!{', '}!'],
            data: {
                keywords: null,
                input: null,
                products: []
            },
            watch: {
                input: debounce(function (newVal) {
                    this.input = newVal;
                    this.fetch();
                }, 500)
            },
            methods: {
                fetch() {
                    this.keywords = this.input;
                    var url = '/admin/search/' + this.keywords;
                    if (this.keywords.length > 0) {
                        axios.get(url).then(response => this.products = response.data);
                    }
                    else{
                        this.products = null;
                    }
                },
                highlight(text)
                {
                    return text.replace(new RegExp(this.keywords, 'gi'), '<b style="color: #62b3ee">$&</b>');
                }
            }
        });
    }
};
