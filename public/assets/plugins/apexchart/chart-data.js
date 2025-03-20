'use strict';

$(document).ready(function() {
    // Fetch teacher and student data
    $.ajax({
        url: '/api/dashboard/stats',
        method: 'GET',
        success: function(response) {
            const teacherData = response.teacherData;
            const studentData = response.studentData;
            
            // Initialize charts after data is loaded
            initializeCharts(teacherData, studentData);
        },
        error: function(error) {
            console.error('Error fetching dashboard data:', error);
        }
    });
    
    function initializeCharts(teacherData, studentData) {
        // Area Chart
        if ($('#apexcharts-area').length > 0) {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];
            const teacherCounts = countByMonth(teacherData, months);
            const studentCounts = countByMonth(studentData, months);
            
            var options = {
                chart: {
                    height: 350,
                    type: "line",
                    toolbar: { show: false },
                },
                dataLabels: { enabled: false },
                stroke: { curve: "smooth" },
                series: [
                    {
                        name: "Teachers",
                        color: '#3D5EE1',
                        data: teacherCounts
                    },
                    {
                        name: "Students",
                        color: '#70C4CF',
                        data: studentCounts
                    }
                ],
                xaxis: {
                    categories: months,
                }
            };
            
            var chart = new ApexCharts(document.querySelector("#apexcharts-area"), options);
            chart.render();
        }
        
        // School Area Chart
        if ($('#school-area').length > 0) {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];
            const teacherCounts = countByMonth(teacherData, months);
            const studentCounts = countByMonth(studentData, months);
            
            var options = {
                chart: {
                    height: 350,
                    type: "area",
                    toolbar: { show: false },
                },
                dataLabels: { enabled: false },
                stroke: { curve: "straight" },
                series: [
                    {
                        name: "Teachers",
                        color: '#3D5EE1',
                        data: teacherCounts
                    },
                    {
                        name: "Students",
                        color: '#70C4CF',
                        data: studentCounts
                    }
                ],
                xaxis: {
                    categories: months,
                }
            };
            
            var chart = new ApexCharts(document.querySelector("#school-area"), options);
            chart.render();
        }
        
        // Bar Chart
        if ($('#bar').length > 0) {
            // Process student data by gender
            const years = [2009, 2010, 2011, 2012, 2013, 2014, 2015, 2016, 2017, 2018, 2019, 2020];
            const boysData = countStudentsByGenderAndYear(studentData, 'Male', years);
            const girlsData = countStudentsByGenderAndYear(studentData, 'Female', years);
            
            var optionsBar = {
                chart: {
                    type: 'bar',
                    height: 350,
                    width: '100%',
                    stacked: false,
                    toolbar: { show: false },
                },
                dataLabels: { enabled: false },
                plotOptions: {
                    bar: {
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                series: [
                    {
                        name: "Boys",
                        color: '#70C4CF',
                        data: boysData,
                    },
                    {
                        name: "Girls",
                        color: '#3D5EE1',
                        data: girlsData,
                    }
                ],
                labels: years,
                xaxis: {
                    labels: { show: false },
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                },
                yaxis: {
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#777' } }
                },
                title: {
                    text: '',
                    align: 'left',
                    style: { fontSize: '18px' }
                }
            };
            
            var chartBar = new ApexCharts(document.querySelector('#bar'), optionsBar);
            chartBar.render();
        }
        
        // Other charts remain unchanged or can be similarly updated
        initializeOtherCharts();
    }
    
    function initializeOtherCharts() {
        if ($('#s-line').length > 0) {
            var sline = {
                chart: {
                    height: 350,
                    type: 'line',
                    zoom: { enabled: false },
                    toolbar: { show: false, }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'straight' },
                series: [{ name: "Desktops", data: [10, 41, 35, 51, 49, 62, 69, 91, 148] }],
                title: { text: 'Product Trends by Month', align: 'left' },
                grid: { row: { colors: ['#f1f2f3', 'transparent'], opacity: 0.5 }, },
                xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'], },
            }
            var chart = new ApexCharts(document.querySelector("#s-line"), sline);
            chart.render();
        }
        
        // Continue with other charts...
        // Donut Chart
        if ($('#donut-chart').length > 0) {
            var donutChart = {
                chart: {
                    height: 350,
                    type: 'donut',
                    toolbar: { show: false, }
                },
                series: [44, 55, 41, 17],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: { width: 200 },
                        legend: { position: 'bottom' }
                    }
                }]
            }
            var donut = new ApexCharts(document.querySelector("#donut-chart"), donutChart);
            donut.render();
        }
        
        // Radial Chart
        if ($('#radial-chart').length > 0) {
            var radialChart = {
                chart: {
                    height: 350,
                    type: 'radialBar',
                    toolbar: { show: false, }
                },
                plotOptions: {
                    radialBar: {
                        dataLabels: {
                            name: { fontSize: '22px', },
                            value: { fontSize: '16px', },
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function(w) { return 249 }
                            }
                        }
                    }
                },
                series: [44, 55, 67, 83],
                labels: ['Apples', 'Oranges', 'Bananas', 'Berries'],
            }
            var chart = new ApexCharts(document.querySelector("#radial-chart"), radialChart);
            chart.render();
        }
    }
    
    // Helper function to count data by month
    function countByMonth(data, months) {
        const monthlyCounts = Array(months.length).fill(0);
        
        data.forEach(item => {
            const joinDate = new Date(item.join_date);
            const monthIndex = joinDate.getMonth();
            
            if (monthIndex < months.length) {
                monthlyCounts[monthIndex]++;
            }
        });
        
        return monthlyCounts;
    }
    
    // Helper function to count students by gender and year
    function countStudentsByGenderAndYear(students, gender, years) {
        const yearCounts = Array(years.length).fill(0);
        
        students.forEach(student => {
            if (student.gender === gender) {
                const joinDate = new Date(student.created_at || student.join_date);
                const year = joinDate.getFullYear();
                const yearIndex = years.indexOf(year);
                
                if (yearIndex !== -1) {
                    yearCounts[yearIndex]++;
                }
            }
        });
        
        return yearCounts;
    }
});