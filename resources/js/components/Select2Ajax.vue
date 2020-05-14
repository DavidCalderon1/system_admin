<template>
    <select style="width: 100%;">
        <slot></slot>
    </select>
</template>

<script>
    let response =  [];
    export default {
        name: "Select2Ajax",
        props: ['options', 'value', 'url'],
        data: function () {

            return {

                ajaxOptions: {
                    url: this.url,
                    dataType: 'json',
                    delay: 250,
                    tags: true,
                    data: function (params) {
                        return {
                            term: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        response = data;
                        return {
                            results: data,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                }
            };
        },
        mounted: function () {
            let vm = this

            $(this.$el)
                .select2({
                    placeholder: "Click to see options",
                    ajax: this.ajaxOptions,
                    dropdownAutoWidth:'true'
                })
                .val(this.value)
                .trigger('change')
                .on('change', function (e) {
                    vm.$emit('input', this.value)
                    vm.$emit('response', {data : response, selected: this.value})
                })
        },
        watch: {
            url: function (value) {
                this.ajaxOptions.url = this.url;
                $(this.$el).select2({ajax: this.ajaxOptions});
            },
            value: function (value) {
                $(this.$el).select2({
                    placeholder: "Click to see options",
                    data: response,
                    dropdownAutoWidth:'true'
                }).val(value).trigger("change");
            },
            options: function (options) {
                $(this.$el).select2({
                    placeholder: "Click to see options",
                    data: options,
                    ajax: this.ajaxOptions,
                    dropdownAutoWidth:'true'
                });
            }
        },

    }
</script>

<style scoped>

</style>
