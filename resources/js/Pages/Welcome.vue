<template>
    <Head title="Welcome" />

    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            <template v-if="$page.props.auth.user">
                <Link :href="route('dashboard')" class="text-sm text-gray-700 underline mr-5">
                    Dashboard
                </Link>

                <Link :href="route('logout')" method="post" class="text-sm text-gray-700 underline">
                    Log Out
                </Link>
            </template>

            <template v-else>
                <Link :href="route('login')" class="text-sm text-gray-700 underline">
                    Log in
                </Link>

                <Link v-if="canRegister" :href="route('register')" class="ml-4 text-sm text-gray-700 underline">
                    Register
                </Link>
            </template>
        </div>

        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="flex rounded shadow-lg bg-white">
                <div class="w-96 p-6 m-10">
                    <h1 class="text-red-400 text-center font-semibold text-2xl mb-5">Covid-19 Problem</h1>

                    <div class="mb-10 py-3 font-bold">
                        <p class="text-center">Keep up to date with the pandemic globally and track individual countries</p>
                    </div>

                    <a href="/register" class="flex justify-center rounded shadow hover:shadow-lg bg-red-800 hover:bg-red-300 px-5 py-3 mt-10">
                        <span class="text-white">Get Started</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3';

export default {
    components: {
      Head,
      Link,
    },
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
        laravelVersion: String,
        phpVersion: String,
    },
}
</script>
