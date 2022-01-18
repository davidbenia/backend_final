<template>
    <div class="border-2 border-black p-1">
        <p>{{this.cases}}</p>
        <p>Cases Today</p>
    </div>

    <div class="border-2 border-black p-1">
        <p>{{this.deaths}}</p>
        <p>Deaths Today</p>
    </div>

    <div class="border-2 border-black p-1">
        <p>{{this.recovered}}</p>
        <p>Recovered Today</p>
    </div>
</template>

<script>
export default {
    props: {
        name: {type:String, required: true},
    },
    data(){
        return {
            cases: null,
            deaths: null,
            recovered: null,
        }
    },
    methods: {
        getCovid()
        {
            axios.get(`https://disease.sh/v3/covid-19/countries/${this.name}`)
            .then((response) => {
                this.cases = response.data.todayCases;
                this.deaths = response.data.todayDeaths;
                this.recovered = response.data.todayRecovered;
            })
        }
    },
    created() {
        this.getCovid();
    }
}
</script>
