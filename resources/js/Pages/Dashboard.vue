<template>
    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Covid-19 Problem - View world-wide statistics and track individual countries
            </h2>
        </template>

        <div class="MAIN_CONTAINER mt-10 w-full h-full flex">
            <div class="MAP_CONTAINER w-8/12 h-full border-4 mx-2 p-4 grid justify-center bg-white">
                <h1 class="text-2xl font-bold mb-5">World Map</h1>

                <div
                    id="worldMap"
                    class="relative"
                    style="width: 1100px; height: 620px;"
                >
                </div>
            </div>

            <div class="DATA_CONTAINER w-4/12 h-full mx-2 p-4 flex-col">
                <div class="GLOBAL_CONTAINER border-4 p-2 mb-10 bg-white">
                    <h1 class="text-2xl font-bold mb-5">Global Statistics</h1>

                    <div class="border-2 border-black p-2">
                        <div class="flex content-center mb-10">
                            <img class="w-12 h-12" src="/img/globe.png"/>
                            <h1 class="ml-5 text-2xl font-semibold">World</h1>
                        </div>

                        <div class="flex gap-4 justify-center">
                            <div class="border-2 border-black p-1">
                                <p>{{ this.globalData.Cases }}</p>
                                <p>Cases Today</p>
                            </div>
                            <div class="border-2 border-black p-1">
                                <p>{{ this.globalData.Deaths }}</p>
                                <p>Deaths Today</p>
                            </div>
                            <div class="border-2 border-black p-1">
                                <p>{{ this.globalData.Recovered }}</p>
                                <p>Recovered Today</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="TRACKED_CONTAINER border-4 bg-white p-2">
                    <h1 class="text-2xl font-bold mb-5">Tracked Statistics</h1>

                    <div class="flex justify-center mb-10">
                        <Select2
                            class="w-72 px-3"
                            v-model="trackedValue"
                            :settings="{
                                width: '100%',
                                ajax: {
                                    url: '/api/countries',
                                    dataType: 'json',
                                }
                            }"
                        />
                        <a href="javascript:void(0)" @click="addTracked" class="px-3 rounded bg-green-400 hover:bg-green-200 shadow hover:shadow-lg ml-4 text-sm font-semibold pt-1">
                            {{ trackText }}
                        </a>
                    </div>

                    <div class="w-full p-3">
                        <div v-for="country in trackedCountries" :key="country.id">
                            <div class="px-1 mb-3 font-semibold border-b-2 border-gray-200 relative">
                                <div class="border-2 border-black p-2">
                                    <div class="flex content-center mb-10">
                                        <MyImg :name="country.name"/>
                                        <h1 class="ml-5 text-2xl font-semibold">{{ country.name }}</h1>
                                        <a href="javascript:void(0)" @click="() => deleteTracked(country.id)" class="absolute right-0 text-red-700">
                                            <img src="https://img.icons8.com/color/48/000000/delete-forever.png"/>
                                        </a>
                                    </div>

                                    <div class="flex gap-4 justify-center">
                                        <covid :name="country.name"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
import Select2 from 'vue3-select2-component';
import MyImg from '@/Components/MyImg.vue';
import Covid from '@/Components/Covid.vue';

export default {
    components: {
        BreezeAuthenticatedLayout,
        Head,
        Select2,
        MyImg,
        Covid,
    },
    data() {
        return {
            trackValue: '',
            trackedCountries: [],
            trackText: 'ADD',
            map: null,
            countryObject: {},
            globalData: {}
        }
    },
    methods: {
        getTracked()
        {
            axios.get("/api/countries/tracked")
            .then((response) => {
                this.trackedCountries = response.data;
                this.fillMap();
            })
            .catch((e) => console.log(e));
        },
        addTracked()
        {
            if(this.trackedValue !== ''){
                this.trackText = "Loading";

                axios.post('/api/countries', {
                    id: this.trackedValue
                })
                .then(() => {
                    this.getTracked();
                    this.fillMap();
                    this.trackText = "ADD"
                });
            }
        },
        deleteTracked(country_id)
        {
            let url = `/api/countries/${country_id}`

            axios.delete(url)
            .then(() => {
                this.getTracked();
                this.fillMap();
            });
        },
        loadMap()
        {
            this.map = new Datamap({
                element: document.getElementById("worldMap"),
                projection: 'mercator',
                fills: {
                    defaultFill: "#C1C1C1",
                    trackedFill: "#51F33B",
                },
                data: {

                }
            });
        },
        fillMap()
        {
            this.map.updateChoropleth(null, {reset: true});

            this.trackedCountries.forEach(element => {
                this.countryObject[element.code] = {fillKey: 'trackedFill'};
                this.map.updateChoropleth(this.countryObject);
            });
        },
        getGlobal()
        {
            axios.get(`https://disease.sh/v3/covid-19/all`)
            .then((response) => {
                this.globalData['Cases'] = response.data.todayCases;
                this.globalData['Deaths'] = response.data.todayDeaths;
                this.globalData['Recovered'] = response.data.todayRecovered;
            })
        },
    },
    mounted() {
        this.getTracked();
        this.loadMap();
        this.fillMap();
    },
    created() {
        this.getGlobal();
    }
}
</script>
