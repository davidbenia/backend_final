<template>
    <img class="w-18 h-12" :src= this.url />
</template>

<script>
export default {
    props: {
        name: {type:String, required: true}
    },
    data(){
        return {
            url: '',
        }
    },
    methods: {
        getFlag()
        {
            axios.get(`https://disease.sh/v3/covid-19/countries/${this.name}`)
            .then((response) => {
                this.url = response.data.countryInfo.flag;
            })
        },
    },
    created() {
        this.getFlag();
    }
}
</script>
