<template>
    <select class="form-control form-control-sm">
        <option></option>
    </select>
</template>

<script>
    export default {
        name: "SelectComponent",
        props: ['options', 'value', 'placeholder', 'url'],
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
                    width: 'resolve',
                    dropdownAutoWidth: 'true',
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
                    width: 'resolve',
                    dropdownAutoWidth: 'true',
                    data: this.options
                }).on('change', function (e) {
                    self.$emit('input', $(e.target).val())
                    self.$emit('response', self.response)
                });
            }
        },
        watch: {
            value(value) {
                let select = $(this.$el).select2();
                select.val(value).trigger("change");
            },
            options(newOpts) {

                if (typeof this.options === "undefined") {

                    this.$select2.empty().html('<option></option>').select2({
                        placeholder: this.placeholder,
                        width: 'resolve',
                        dropdownAutoWidth: 'true',
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
                    });
                } else {
                    this.$select2.empty().html('<option></option>').select2({
                        placeholder: this.placeholder,
                        width: 'resolve',
                        dropdownAutoWidth: 'true',
                        data: this.options
                    });
                }
            },
        }
    }
</script>

<style scoped>
    .bigdrop {
        width: 600px !important;
    }
</style>
