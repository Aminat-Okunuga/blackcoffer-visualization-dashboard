<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blackcoffer | Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <div id="sidebar-overlay" class="overlay w-100 vh-100 position-fixed d-none"></div>

    <!-- sidebar -->
    <div class="col-md-3 col-lg-2 px-0 position-fixed h-100 bg-white shadow-sm sidebar" id="sidebar">
        <h1 class="bi bi-bootstrap text-primary d-flex my-4 justify-content-center">Blackcoffer</h1>
        <div class="list-group rounded-0">
            <a href="#" class="list-group-item list-group-item-action active border-0 d-flex align-items-center">
                <span class="bi bi-border-all"></span>
                <span class="ml-2">Dashboard</span>
            </a>
        </div>
    </div>

    <div class="col-md-9 col-lg-10 ml-md-auto px-0 ms-md-auto">
        <!-- top nav -->
        <nav class="w-100 d-flex px-4 py-2 mb-4 shadow-sm">
            <!-- close sidebar -->
            <button class="btn py-0 d-lg-none" id="open-sidebar">
                <span class="bi bi-list text-primary h3"></span>
            </button>
            <div>
                <!-- Dropdowns for filter criteria -->
                <label for="endYearFilter">End Year:</label>
                <select id="endYearFilter"></select>

                <!-- Add more dropdowns for other filter criteria -->

                <button onclick="applyFilters()">Filter</button>
            </div>
        </nav>

        <!-- main content -->
        <main class="p-4 min-vh-100">
            <section>
                <div class="row ">
                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title text-center">Intensity vs Year</h3>
                                <canvas id="intensityChart" width="600" height="500"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title text-center">Likelihood vs Year</h3>
                                <canvas id="likelihoodChart" width="600" height="500"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title text-center">Relevance vs Year</h3>
                                <canvas id="relevanceChart" width="600" height="500"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title text-center">Total Countries</h3>
                                <canvas id="countryPieChart" width="600" height="500"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title text-center">Regions Based on Relevance</h3>
                                <canvas id="regionRelevanceAreaChart" width="600" height="500"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title text-center">Cities Longitude and Latitude</h3>
                                <canvas id="clusteredBarChart" width="800" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12 col-lg-12 chart-container">
                        {{-- <div class="card">
                            <div class="card-body"> --}}
                        <h3 class="card-title text-center">Topics Based on Likelihood</h3>
                        <canvas id="topicLikelihoodPieChart" width="600" height="500"></canvas>
                        {{-- </div>
                        </div> --}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12 mt-3">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title text-center">City vs Relevance</h3>
                                <canvas id="CityRelevanceBarChart" width="800" height="500"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-md-12 col-lg-12 mt-3">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title text-center">City vs Relevance</h3>
                                <canvas id="cityRelevancePieChart" width="800" height="500"></canvas>
                            </div>
                        </div>
                    </div>
                </div> --}}

                {{-- filter --}}
                <canvas id="filteredChart" width="800" height="400"></canvas>

            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    {{-- display populated chart for intensity --}}
    <script>
        // Function to fetch data from the API and create/update the chart
        function fetchDataAndUpdateChart() {
            axios.get('/api/dashboard-data')
                .then(response => {
                    const apiData = response.data.data;

                    if (!Array.isArray(apiData)) {
                        throw new Error('Invalid API response format');
                    }


                    // extract city from api
                    const cities = apiData.map(item => item.city);
                    const cityRelevance = apiData.map(item => item.relevance);
                    const longitudes = apiData.map(item => item.citylng);
                    const latitudes = apiData.map(item => item.citylat);
                    const intensities = apiData.map(item => item.intensity);
                    const likelihoods = apiData.map(item => item.likelihood);
                    const years = [...new Set(apiData.map(item => item.end_year))];
                    const countryData = {}; // Object to store country data

                    // Count occurrences of each country
                    apiData.forEach(item => {
                        const country = item.country || 'Unknown'; // Use 'Unknown' for missing country data
                        if (countryData[country]) {
                            countryData[country]++;
                        } else {
                            countryData[country] = 1;
                        }
                    });

                    const countryLabels = Object.keys(countryData);
                    const countryCounts = Object.values(countryData);

                    // Extract unique regions
                    const uniqueRegions = [...new Set(apiData.map(item => item.region))];

                    // Calculate relevance for each region
                    const regionRelevanceData = uniqueRegions.map(region => ({
                        region,
                        relevance: apiData
                            .filter(item => item.region === region)
                            .reduce((total, item) => total + item.relevance, 0),
                    }));

                    // Sort regions by relevance in descending order
                    regionRelevanceData.sort((a, b) => b.relevance - a.relevance);

                    // Extract labels (regions) and data (relevance) for the chart
                    const labels = regionRelevanceData.map(item => item.region);
                    const relevance = regionRelevanceData.map(item => item.relevance);

                    // Calculate likelihood for each topic
                    const topicLikelihoodData = {};

                    apiData.forEach(item => {
                        const topic = item.topic;
                        const likelihood = item.likelihood;

                        if (!topicLikelihoodData[topic]) {
                            topicLikelihoodData[topic] = 0;
                        }

                        topicLikelihoodData[topic] += likelihood;
                    });

                    // Extract labels (topics) and data (likelihood) for the pie chart
                    const topicLabels = Object.keys(topicLikelihoodData);
                    const likelihood = Object.values(topicLikelihoodData);

                    // Call the function to create or update the chart with API data
                    createOrUpdateChart(intensities, years);
                    // Call the function to create or update the chart with API data
                    createOrUpdateLikelihoodChart(likelihoods, years);
                    // Call the function to create or update the chart with API data
                    createOrUpdateRelevanceChart(likelihoods, years);
                    // Call the function to create/update the pie chart with API data
                    createOrUpdateCountryPieChart(countryLabels, countryCounts);
                    // Call the function to create or update the area chart
                    createOrUpdateRegionRelevanceAreaChart(labels, relevance);
                    // Call the function to create or update the topic likelihood pie chart
                    createOrUpdateTopicLikelihoodPieChart(topicLabels, likelihood);
                    createOrUpdateCityLongLatBarChart(cities, longitudes, latitudes);
                    createOrUpdateCityRelevanceBarChart(cities, cityRelevance);
                    // createOrUpdateCityRelevancePieChart(cities, cityRelevance);

                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        // Function to create or update the chart using API data and unique intensities
        function createOrUpdateChart(intensities, years) {
            if (typeof intensityChart === 'undefined') {
                // Sort the years array in ascending order
                years.sort((a, b) => a - b);


                // Find the minimum and maximum intensities
                const minIntensity = Math.min(...intensities);
                const maxIntensity = Math.max(...intensities);

                const ctx = document.getElementById('intensityChart').getContext('2d');
                intensityChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: years, // Use years as labels
                        datasets: [{
                            label: 'Intensity',
                            data: intensities,
                            backgroundColor: 'rgba(254, 162, 235, 0.5)', // Increase opacity
                            borderColor: 'rgba(254, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Year'
                                }
                            },
                            y: {
                                beginAtZero: true,

                                title: {
                                    display: true,
                                    text: 'Intensity'
                                },
                                ticks: {
                                    stepSize: 10, // Set the step size to 10
                                    max: Math.ceil(Math.max(...intensities) / 10) *
                                        10, // Calculate the maximum value for the y-axis
                                    min: Math.floor(Math.min(...intensities) / 10) *
                                        10 // Calculate the minimum value for the y-axis
                                }
                            }
                        }
                    }
                });
            } else {
                // Update the chart if it already exists
                intensityChart.data.labels = years;
                intensityChart.data.datasets[0].data = intensities;
                intensityChart.update();
            }
        }

        // Function to create or update the chart using API data and unique likelihoods
        function createOrUpdateLikelihoodChart(likelihoods, years) {
            if (typeof likelihoodChart === 'undefined') {
                // Sort the years array in ascending order
                years.sort((a, b) => a - b);


                // Find the minimum and maximum likelihoods
                const minLikelihood = Math.min(...likelihoods);
                const maxLikelihood = Math.max(...likelihoods);

                const ctx = document.getElementById('likelihoodChart').getContext('2d');
                intensityChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: years, // Use years as labels
                        datasets: [{
                            label: 'Likelihood',
                            data: likelihoods,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)', // Increase opacity
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Year'
                                }
                            },
                            y: {
                                beginAtZero: true,

                                title: {
                                    display: true,
                                    text: 'Likelihood'
                                },
                                ticks: {
                                    stepSize: 1, // Set the step size to 10
                                    max: Math.ceil(Math.max(...likelihoods) / 1) *
                                        1, // Calculate the maximum value for the y-axis
                                    min: Math.floor(Math.min(...likelihoods) / 1) *
                                        1 // Calculate the minimum value for the y-axis
                                }
                            }
                        }
                    }
                });
            } else {
                // Update the chart if it already exists
                likelihoodChart.data.labels = years;
                likelihoodChart.data.datasets[0].data = likelihoods;
                likelihoodChart.update();
            }
        }

        // Function to create or update the relevance chart
        function createOrUpdateRelevanceChart(relevances, years) {
            if (typeof relevanceChart === 'undefined') {
                // Sort the years array in ascending order
                years.sort((a, b) => a - b);

                // Create the line chart if it doesn't exist
                const ctx = document.getElementById('relevanceChart').getContext('2d');
                relevanceChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: years,
                        datasets: [{
                            label: 'Relevance',
                            data: relevances,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            fill: false // Don't fill the area under the line
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Year'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Relevance'
                                }
                            }
                        }
                    }
                });
            } else {
                // Update the line chart if it already exists
                relevanceChart.data.labels = years;
                relevanceChart.data.datasets[0].data = relevances;
                relevanceChart.update();
            }
        }

        // Function to create or update the pie chart
        function createOrUpdateCountryPieChart(labels, counts) {
            if (typeof pieChart === 'undefined') {
                // Create the pie chart if it doesn't exist
                const ctx = document.getElementById('countryPieChart').getContext('2d');
                pieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: counts,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.5)',
                                'rgba(54, 162, 235, 0.5)',
                                'rgba(255, 206, 86, 0.5)',
                                'rgba(55, 206, 56, 0.5)',
                                'rgba(35, 116, 86, 0.5)',
                                'rgba(230, 206, 86, 0.5)',
                                'rgba(130, 17, 80, 0.5)',
                                'rgba(110, 26, 36, 0.5)',
                                'rgba(238, 130, 238, 0.5)',
                                'rgba(60, 79, 13, 0.5)',
                                'rgba(60, 179, 113, 0.5)',
                                'rgba(60, 179, 11, 0.5)',
                                'rgba(30, 69, 113, 0.5)',
                                'rgba(70, 225, 223, 0.5)',
                                'rgba(60, 129, 103, 0.5)',
                                'rgba(50, 255, 143, 0.5)',
                                'rgba(160, 179, 113, 0.5)',
                                'rgba(100, 179, 113, 0.5)',
                                'rgba(220, 179, 131, 0.5)',
                                'rgba(120, 129, 123, 0.5)',
                                'rgba(120, 255, 223, 0.5)',
                                'rgba(255, 255, 123, 0.5)',
                                'rgba(240, 179, 255, 0.5)',
                                'rgba(220, 129, 238, 0.5)',
                                'rgba(140, 229, 123, 0.5)',
                                'rgba(250, 129, 122, 0.5)',
                                'rgba(150, 109, 100, 0.5)',
                                'rgba(200, 19, 12, 0.5)',
                                'rgba(210, 212, 122, 0.5)',
                                'rgba(190, 129, 255, 0.5)',
                            ],
                        }]
                    },
                    options: {
                        responsive: true,
                    }
                });
            } else {
                // Update the pie chart if it already exists
                pieChart.data.labels = labels;
                pieChart.data.datasets[0].data = counts;
                pieChart.update();
            }
        }

        // Function to create or update the region relevance area chart
        function createOrUpdateRegionRelevanceAreaChart(labels, relevance) {
            if (typeof regionAreaChart === 'undefined') {
                // Create the chart if it doesn't exist
                const ctx = document.getElementById('regionRelevanceAreaChart').getContext('2d');
                regionAreaChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Relevance',
                            data: relevance,
                            fill: true, // Enable area fill
                            backgroundColor: 'rgba(54, 255, 235, 0.5)',
                            borderColor: 'rgba(54, 255, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Region'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Relevance'
                                }
                            }
                        }
                    }
                });
            } else {
                // Update the chart if it already exists
                regionAreaChart.data.labels = labels;
                regionAreaChart.data.datasets[0].data = relevance;
                regionAreaChart.update();
            }
        }

        // Function to create or update the topic likelihood pie chart
        function createOrUpdateTopicLikelihoodPieChart(topicLabels, likelihood) {
            if (typeof topicLikelihoodPieChart === 'undefined') {
                const ctx = document.getElementById('topicLikelihoodPieChart').getContext('2d');
                const topicLikelihoodPieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: topicLabels,
                        datasets: [{
                            data: likelihood,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.5)',
                                'rgba(54, 162, 235, 0.5)',
                                'rgba(255, 206, 86, 0.5)',
                                'rgba(255, 255, 255, 0.5)',
                                'rgba(225, 251, 55, 0.5)',
                                'rgba(55, 255, 45, 0.5)',
                                'rgba(205, 25, 25, 0.5)',
                                'rgba(128, 0, 128, 0.5)',
                                'rgba(255, 0, 0, 0.5)',
                                'rgba(0, 255, 0, 0.5)',
                                'rgba(0, 0, 255, 0.5)',
                                'rgba(255, 255, 0, 0.5)',
                                'rgba(255, 0, 255, 0.5)',
                                'rgba(0, 255, 255, 0.5)',
                                'rgba(128, 0, 0, 0.5)',
                                'rgba(0, 128, 0, 0.5)',
                                'rgba(0, 0, 128, 0.5)',
                                'rgba(128, 128, 0, 0.5)',
                                'rgba(128, 0, 128, 0.5)',
                                'rgba(128, 0, 0, 0.5)',
                                'rgba(128, 128, 128, 0.5)',
                                'rgba(255, 128, 0, 0.5)',
                                'rgba(255, 0, 128, 0.5)',
                                'rgba(128, 255, 0, 0.5)',
                                'rgba(128, 255, 0, 0.5)',
                                'rgba(128, 0, 255, 0.5)',
                                // 'rgba(110, 0, 110, 0.5)',
                                // Add more colors as needed
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(255, 255, 255, 1)',
                                'rgba(225, 251, 55, 1)',
                                'rgba(55, 255, 45, 1)',
                                'rgba(205, 25, 25, 1)',
                                'rgba(128, 0, 128, 1)',
                                'rgba(255, 0, 0, 1)',
                                'rgba(0, 255, 0, 1)',
                                'rgba(0, 0, 255, 1)',
                                'rgba(255, 255, 0, 1)',
                                'rgba(255, 0, 255, 1)',
                                'rgba(0, 255, 255, 1)',
                                'rgba(128, 0, 0, 1)',
                                'rgba(0, 128, 0, 1)',
                                'rgba(0, 0, 128, 1)',
                                'rgba(128, 128, 0, 1)',
                                'rgba(128, 0, 128, 1)',
                                'rgba(128, 0, 0, 1)',
                                'rgba(128, 128, 128, 1)',
                                'rgba(255, 128, 0, 1)',
                                'rgba(255, 0, 128, 1)',
                                'rgba(128, 255, 0, 1)',
                                'rgba(128, 255, 0, 1)',
                                'rgba(128, 0, 255, 1)',

                                // Add more colors as needed
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            } else {
                // Update the pie chart if it already exists
                topicLikelihoodPieChart.data.labels = topicLabels;
                topicLikelihoodPieChart.data.datasets[0].data = likelihood;
                topicLikelihoodPieChart.update();
            }
        }

        // function createOrUpdateCityLongLatBarChart(cities, longitudes, latitudes) {
        //     const ctx = document.getElementById('clusteredBarChart').getContext('2d');
        //     const clusteredBarChart = new Chart(ctx, {
        //         type: 'bar',
        //         data: {
        //             labels: cities,
        //             datasets: [{
        //                     label: 'Longitude',
        //                     data: longitudes,
        //                     backgroundColor: 'rgba(75, 192, 192, 0.5)',
        //                     borderColor: 'rgba(75, 192, 192, 1)',
        //                     borderWidth: 1,
        //                     yAxisID: 'longitude-axis',
        //                 },
        //                 {
        //                     label: 'Latitude',
        //                     data: latitudes,
        //                     backgroundColor: 'rgba(255, 99, 132, 0.5)',
        //                     borderColor: 'rgba(255, 99, 132, 1)',
        //                     borderWidth: 1,
        //                     yAxisID: 'latitude-axis',
        //                 },
        //             ],
        //         },
        //         options: {
        //             scales: {
        //                 x: {
        //                     beginAtZero: true,
        //                     title: {
        //                         display: true,
        //                         text: 'Cities',
        //                     },
        //                 },
        //                 y: {
        //                     beginAtZero: true,
        //                     position: 'left',
        //                     title: {
        //                         display: true,
        //                         text: 'Longitude',
        //                     },
        //                     id: 'longitude-axis',
        //                 },
        //                 y1: {
        //                     beginAtZero: true,
        //                     position: 'right',
        //                     title: {
        //                         display: true,
        //                         text: 'Latitude',
        //                     },
        //                     id: 'latitude-axis',
        //                 },
        //             },
        //         },
        //     });
        // }

        function createOrUpdateCityLongLatBarChart(cities, longitudes, latitudes) {

            let clusteredBarChart; // Declare the chart variable outside the conditional
            if (typeof clusteredChart === 'undefined') {
                const ctx = document.getElementById('clusteredBarChart').getContext('2d');
                clusteredChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: cities,
                        datasets: [{
                                label: 'Longitude',
                                data: longitudes,
                                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 3,
                                yAxis: 'longitude-axis', // Use yAxis instead of yAxisID
                            },
                            {
                                label: 'Latitude',
                                data: latitudes,
                                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 3,
                                yAxis: 'latitude-axis', // Use yAxis instead of yAxisID
                            },
                        ],
                    },
                    options: {
                        scales: {
                            x: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Cities',
                                },
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Longitude / Latitude',
                                },
                            },
                        },
                    },
                });
            } else {
                // Update the chart if it already exists
                clusteredChart.data.labels = cities;
                clusteredChart.data.datasets[0].data = longitudes;
                clusteredChart.data.datasets[0].data = latitudes;
                clusteredChart.update();
            }
        }

        // for city vs relevance bar chart
        function createOrUpdateCityRelevanceBarChart(cities, cityRelevance) {
            let cityRelevanceChart; // Declare the chart variable outside the conditional

            if (typeof cityRelevanceChart === 'undefined') {
                const ctx = document.getElementById('CityRelevanceBarChart').getContext('2d');

                // Initialize the chart variable within this block
                cityRelevanceChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: cities, // City names on the x-axis
                        datasets: [{
                            label: 'Relevance',
                            data: cityRelevance, // Relevance values on the y-axis
                            backgroundColor: 'rgba(54, 162, 235, 0.5)', // Bar color
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'City'
                                },
                                ticks: {
                                    autoSkip: false, // Ensure all labels are displayed
                                    maxRotation: 90, // Rotate labels for better readability
                                    minRotation: 0
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Relevance'
                                },
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1, // Set the step size to 1
                                    max: Math.ceil(Math.max(...cityRelevance) / 1) *
                                        1, // Calculate the maximum value for the y-axis
                                    min: Math.floor(Math.min(...cityRelevance) / 1) *
                                        1 // Calculate the minimum value for the y-axis
                                }
                            }
                        }
                    }
                });
            } else {
                // Update the chart if it already exists
                cityRelevanceChart.data.labels = cities;
                cityRelevanceChart.data.datasets[0].data = cityRelevance;
                cityRelevanceChart.update();
            }
        }

        // function createOrUpdateCityRelevancePieChart(cities, cityRelevance) {
        //     const ctx = document.getElementById('cityRelevancePieChart').getContext('2d');

        //     if (typeof cityRelevancePieChart === 'undefined') {
        //         // Create the chart if it doesn't exist
        //         cityRelevancePieChart = new Chart(ctx, {
        //             type: 'pie',
        //             data: {
        //                 labels: cities,
        //                 datasets: [{
        //                     data: cityRelevance,
        //                     backgroundColor: [
        //                         'rgba(255, 99, 132, 0.5)',
        //                         'rgba(54, 162, 235, 0.5)',
        //                         'rgba(255, 206, 86, 0.5)',
        //                         'rgba(75, 192, 192, 0.5)',
        //                         'rgba(153, 102, 255, 0.5)',
        //                         // Add more colors as needed
        //                     ],
        //                     borderColor: [
        //                         'rgba(255, 99, 132, 1)',
        //                         'rgba(54, 162, 235, 1)',
        //                         'rgba(255, 206, 86, 1)',
        //                         'rgba(75, 192, 192, 1)',
        //                         'rgba(153, 102, 255, 1)',
        //                         // Add more colors as needed
        //                     ],
        //                     borderWidth: 1
        //                 }]
        //             },
        //             options: {
        //                 responsive: true,
        //                 maintainAspectRatio: false, // Adjust aspect ratio as needed
        //                 title: {
        //                     display: true,
        //                     text: 'City Relevance Pie Chart'
        //                 }
        //             }
        //         });
        //     } else {
        //         // Update the chart if it already exists
        //         cityRelevancePieChart.data.labels = cities;
        //         cityRelevancePieChart.data.datasets[0].data = cityRelevance;
        //         cityRelevancePieChart.update();
        //     }
        // }

        // Initialize chart variable
        // let cityRelevancePieChart;

        // Initialize chart variable
        let intensityChart;
        let likelihoodChart;
        let relevanceChart;
        let pieChart;
        // let topicLikelihoodChart;
        let regionAreaChart;
        let topicLikelihoodPieChart;
        let clusteredBarChart;
        let cityRelevanceChart;

        // Fetch data and create/update the chart
        fetchDataAndUpdateChart();
    </script>

</body>

</html>
