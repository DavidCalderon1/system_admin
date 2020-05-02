<template>
    <select class="form-control form-control-sm">
        <option></option>
    </select>
</template>

<script>
    export default {
        name: "SelectComponent",
        props: ['options', 'placeholder', 'url'],
        data(){
            return{
                response:[],
                return:{},
            }
        },
        mounted() {
            var self = this;

            if (typeof this.options === "undefined") {
                this.$select2 = $(this.$el).select2({
                    placeholder: this.placeholder,
                    ajax: {
                        url: this.url,
                        dataType: 'json',
                        type: "GET",
                        quietMillis: 50,
                        data: function (params) {
                            let queryParameters = {
                                q: params.term
                            }
                            return queryParameters;
                        },
                        processResults: function (data) {
                           self.response = data
                            return {
                                results: data
                            };
                        }
                    }
                }).on('change', function (e) {

                    let response = self.response.filter(item => {
                        if(item.id ==  $(e.target).val()){
                            self.return = item;
                        }
                    });

                    self.$emit('input', $(e.target).val())
                    self.$emit('response', self.return )
                });
            } else {
                this.$select2 = $(this.$el).select2({
                    placeholder: this.placeholder,
                    data: this.options
                }).on('change', function (e) {
                    self.$emit('input', $(e.target).val())
                    self.$emit('response', self.response)
                });
            }
        },
        watch: {
            options(newOpts) {
                if (typeof this.options === "undefined") {

                    this.$select2.empty().html('<option></option>').select2({
                        placeholder: this.placeholder,
                        ajax: {
                            url: this.url,
                            dataType: 'json',
                            type: "GET",
                            quietMillis: 50,
                            data: function (params) {
                                let queryParameters = {
                                    q: params.term
                                }
                                return queryParameters;
                            },
                            processResults: function (data) {
                                return {
                                    results: data
                                };
                            }
                        }
                    })
                } else {
                    this.$select2.empty().html('<option></option>').select2({
                        placeholder: this.placeholder,
                        data: this.options
                    })
                }
            },
        }
    }
</script>

<style scoped>

</style>
