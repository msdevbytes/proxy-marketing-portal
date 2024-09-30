@vite('resources/css/app.css')
<x-filament-panels::page>

    <section id="pm-dashboard" class="grid md:grid-cols-2 lg:grid-cols-3 grid-cols-1 grid-rows-1 gap-5">
        <div class="flex flex-col rounded-md overflow-hidden shadow-md">
            <div
                class="bg-gradient-to-t from-red-500 to-red-700 dark:bg-gradient-to-tr dark:from-red-950 dark:to-red-700 p-5 text-white">
                <p class="text-lg">Product Status Summary</p>
                <div class="flex text-center flex-col pt-8 pb-10 gap-1">
                    <span>Active Products</span>
                    <span class="font-bold text-3xl">689.00</span>

                    <span>Disabled Products</span>
                    <span class="font-bold   text-3xl">340.00</span>
                </div>
            </div>
            <div
                class="flex bg-gradient-to-tr from-gray-200 to-gray-50 dark:bg-gradient-to-tr dark:from-gray-900 dark:to-gray-600 p-10">
                <div class="flex flex-col gap-3 w-full -mt-20 font-semibold dark:text-gray-200 text-gray-900">
                    <div
                        class="rounded-md py-6 dark:bg-gray-950  bg-gray-50 p-5 flex md:flex-row flex-col md:gap-0 gap-4 md:justify-between justify-center items-center">
                        <div class="flex md:flex-row flex-col items-center justify-start md:gap-2 gap-4">
                            @svg('bi-compass', 'w-8 h-8')
                            <p>Today : Active / Disabled</p>
                        </div>
                        <p class="md:text-base text-3xl">689 / 340</p>
                    </div>
                    <div
                        class="rounded-md py-6 dark:bg-gray-950  bg-gray-50 p-5 flex md:flex-row flex-col md:gap-0 gap-4 md:justify-between justify-center items-center">
                        <div class="flex md:flex-row flex-col items-center justify-start md:gap-2 gap-4">
                            @svg('heroicon-o-chart-bar-square', 'w-8 h-8')
                            <p>September : Active / Disabled</p>
                        </div>
                        <p class="md:text-base text-3xl">689 / 340</p>
                    </div>

                    <div
                        class="rounded-md py-6 dark:bg-gray-950  bg-gray-50 p-5 flex md:flex-row flex-col md:gap-0 gap-4 md:justify-between justify-center items-center">
                        <div class="flex md:flex-row flex-col items-center justify-start md:gap-2 gap-4">
                            @svg('heroicon-o-square-3-stack-3d', 'w-8 h-8')
                            <p>Overall : Active / Disabled</p>
                        </div>
                        <p class="md:text-base text-3xl">689 / 340</p>
                    </div>

                </div>
            </div>
        </div>
        <div class="flex flex-col rounded-md overflow-hidden shadow-md">
            <div
                class="bg-gradient-to-t from-green-500 to-green-700 dark:bg-gradient-to-tr dark:from-green-950 dark:to-green-700 p-5 text-white">
                <p class="text-lg">Product Status Summary</p>
                <div class="flex text-center flex-col pt-8 pb-10 gap-1">
                    <span>Active Products</span>
                    <span class="font-bold text-3xl">689.00</span>

                    <span>Disabled Products</span>
                    <span class="font-bold   text-3xl">340.00</span>
                </div>
            </div>
            <div
                class="flex bg-gradient-to-tr from-gray-200 to-gray-50 dark:bg-gradient-to-tr dark:from-gray-900 dark:to-gray-600 p-10">
                <div class="flex flex-col gap-3 w-full -mt-20 font-semibold dark:text-gray-200 text-gray-900">
                    <div
                        class="rounded-md py-6 dark:bg-gray-950  bg-gray-50 p-5 flex md:flex-row flex-col md:gap-0 gap-4 md:justify-between justify-center items-center">
                        <div class="flex md:flex-row flex-col items-center justify-start md:gap-2 gap-4">
                            @svg('bi-compass', 'w-8 h-8')
                            <p>Today : Active / Disabled</p>
                        </div>
                        <p class="md:text-base text-3xl">689 / 340</p>
                    </div>
                    <div
                        class="rounded-md py-6 dark:bg-gray-950  bg-gray-50 p-5 flex md:flex-row flex-col md:gap-0 gap-4 md:justify-between justify-center items-center">
                        <div class="flex md:flex-row flex-col items-center justify-start md:gap-2 gap-4">
                            @svg('heroicon-o-chart-bar-square', 'w-8 h-8')
                            <p>September : Active / Disabled</p>
                        </div>
                        <p class="md:text-base text-3xl">689 / 340</p>
                    </div>

                    <div
                        class="rounded-md py-6 dark:bg-gray-950  bg-gray-50 p-5 flex md:flex-row flex-col md:gap-0 gap-4 md:justify-between justify-center items-center">
                        <div class="flex md:flex-row flex-col items-center justify-start md:gap-2 gap-4">
                            @svg('heroicon-o-square-3-stack-3d', 'w-8 h-8')
                            <p>Overall : Active / Disabled</p>
                        </div>
                        <p class="md:text-base text-3xl">689 / 340</p>
                    </div>

                </div>
            </div>
        </div>
        <div class="flex flex-col rounded-md overflow-hidden shadow-md">
            <div
                class="bg-gradient-to-t from-yellow-500 to-yellow-700 dark:bg-gradient-to-tr dark:from-yellow-950 dark:to-yellow-700 p-5 text-white">
                <p class="text-lg">Product Status Summary</p>
                <div class="flex text-center flex-col pt-8 pb-10 gap-1">
                    <span>Active Products</span>
                    <span class="font-bold text-3xl">689.00</span>

                    <span>Disabled Products</span>
                    <span class="font-bold   text-3xl">340.00</span>
                </div>
            </div>
            <div
                class="flex bg-gradient-to-tr from-gray-200 to-gray-50 dark:bg-gradient-to-tr dark:from-gray-900 dark:to-gray-600 p-10">
                <div class="flex flex-col gap-3 w-full -mt-20 font-semibold dark:text-gray-200 text-gray-900">
                    <div
                        class="rounded-md py-6 dark:bg-gray-950  bg-gray-50 p-5 flex md:flex-row flex-col md:gap-0 gap-4 md:justify-between justify-center items-center">
                        <div class="flex md:flex-row flex-col items-center justify-start md:gap-2 gap-4">
                            @svg('bi-compass', 'w-8 h-8')
                            <p>Today : Active / Disabled</p>
                        </div>
                        <p class="md:text-base text-3xl">689 / 340</p>
                    </div>
                    <div
                        class="rounded-md py-6 dark:bg-gray-950  bg-gray-50 p-5 flex md:flex-row flex-col md:gap-0 gap-4 md:justify-between justify-center items-center">
                        <div class="flex md:flex-row flex-col items-center justify-start md:gap-2 gap-4">
                            @svg('heroicon-o-chart-bar-square', 'w-8 h-8')
                            <p>September : Active / Disabled</p>
                        </div>
                        <p class="md:text-base text-3xl">689 / 340</p>
                    </div>

                    <div
                        class="rounded-md py-6 dark:bg-gray-950  bg-gray-50 p-5 flex md:flex-row flex-col md:gap-0 gap-4 md:justify-between justify-center items-center">
                        <div class="flex md:flex-row flex-col items-center justify-start md:gap-2 gap-4">
                            @svg('heroicon-o-square-3-stack-3d', 'w-8 h-8')
                            <p>Overall : Active / Disabled</p>
                        </div>
                        <p class="md:text-base text-3xl">689 / 340</p>
                    </div>

                </div>
            </div>
        </div>
        <div class="flex flex-col rounded-md overflow-hidden shadow-md">
            <div
                class="bg-gradient-to-t from-blue-500 to-blue-700 dark:bg-gradient-to-tr dark:from-blue-950 dark:to-blue-700 p-5 text-white">
                <p class="text-lg">Product Status Summary</p>
                <div class="flex text-center flex-col pt-8 pb-10 gap-1">
                    <span>Active Products</span>
                    <span class="font-bold text-3xl">689.00</span>

                    <span>Disabled Products</span>
                    <span class="font-bold   text-3xl">340.00</span>
                </div>
            </div>
            <div
                class="flex bg-gradient-to-tr from-gray-200 to-gray-50 dark:bg-gradient-to-tr dark:from-gray-900 dark:to-gray-600 p-10">
                <div class="flex flex-col gap-3 w-full -mt-20 font-semibold dark:text-gray-200 text-gray-900">
                    <div
                        class="rounded-md py-6 dark:bg-gray-950  bg-gray-50 p-5 flex md:flex-row flex-col md:gap-0 gap-4 md:justify-between justify-center items-center">
                        <div class="flex md:flex-row flex-col items-center justify-start md:gap-2 gap-4">
                            @svg('bi-compass', 'w-8 h-8')
                            <p>Today : Active / Disabled</p>
                        </div>
                        <p class="md:text-base text-3xl">689 / 340</p>
                    </div>
                    <div
                        class="rounded-md py-6 dark:bg-gray-950  bg-gray-50 p-5 flex md:flex-row flex-col md:gap-0 gap-4 md:justify-between justify-center items-center">
                        <div class="flex md:flex-row flex-col items-center justify-start md:gap-2 gap-4">
                            @svg('heroicon-o-chart-bar-square', 'w-8 h-8')
                            <p>September : Active / Disabled</p>
                        </div>
                        <p class="md:text-base text-3xl">689 / 340</p>
                    </div>

                    <div
                        class="rounded-md py-6 dark:bg-gray-950  bg-gray-50 p-5 flex md:flex-row flex-col md:gap-0 gap-4 md:justify-between justify-center items-center">
                        <div class="flex md:flex-row flex-col items-center justify-start md:gap-2 gap-4">
                            @svg('heroicon-o-square-3-stack-3d', 'w-8 h-8')
                            <p>Overall : Active / Disabled</p>
                        </div>
                        <p class="md:text-base text-3xl">689 / 340</p>
                    </div>

                </div>
            </div>
        </div>

    </section>

</x-filament-panels::page>
