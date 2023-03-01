<script setup>
import { onMounted, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import * as Am5 from "@amcharts/amcharts5";
import * as Am5xy from "@amcharts/amcharts5/xy";
import Am5themes_Animated from "@amcharts/amcharts5/themes/Animated";

const props = defineProps(["datos"]);
let datos = props.datos;
let contenedor = ref();
const form = useForm({
    ValorA: "",
    ValorB: "",
});

onMounted(() => {
    const root = Am5.Root.new("chartdiv");

    root.setThemes([Am5themes_Animated.new(root)]);

    let chart = root.container.children.push(
        Am5xy.XYChart.new(root, {
            panX: false,
            panY: false,
            wheelX: "none",
            wheelY: "none",
            layout: root.verticalLayout,
        })
    );

    // Create Y-axis
    let yRenderer = Am5xy.AxisRendererY.new(root, {
        visible: false,
        minGridDistance: 20,
        inversed: true,
    });

    yRenderer.grid.template.set("visible", false);

    let yAxis = chart.yAxes.push(
        Am5xy.CategoryAxis.new(root, {
            renderer: yRenderer,
            categoryField: "category",
        })
    );

    //Create X-axis
    let xRenderer = Am5xy.AxisRendererX.new(root, {
        visible: false,
        minGridDistance: 30,
        inversed: true,
    });

    xRenderer.grid.template.set("visible", false);

    let xAxis = chart.xAxes.push(
        Am5xy.CategoryAxis.new(root, {
            renderer: xRenderer,
            categoryField: "category",
        })
    );

    // Create series
    let series = chart.series.push(
        Am5xy.ColumnSeries.new(root, {
            calculateAggregates: true,
            stroke: Am5.color(0xffffff),
            clustered: false,
            xAxis: xAxis,
            yAxis: yAxis,
            categoryXField: "x",
            categoryYField: "y",
            valueField: "value",
        })
    );

    const seriesTemplate = series.columns.template;
    seriesTemplate.setAll({
        strokeOpacity: 1,
        strokeWidth: 2,
        cornerRadiusTL: 5,
        cornerRadiusTR: 5,
        cornerRadiusBL: 5,
        cornerRadiusBR: 5,
        width: Am5.percent(100),
        height: Am5.percent(100),
        templateField: "columnSettings",
    });

    let circleTemplate = Am5.Template.new({});
    circleTemplate.setAll({
        draggable: true,
    });

    series.set("heatRules", [
        {
            target: circleTemplate,
            min: 10,
            max: 35,
            dataField: "value",
            key: "radius",
        },
    ]);

    series.bullets.push(function () {
        return Am5.Bullet.new(root, {
            sprite: Am5.Circle.new(
                root,
                {
                    fill: Am5.color(0x000000),
                    fillOpacity: 0.5,
                    strokeOpacity: 0,
                },
                circleTemplate
            ),
        });
    });

    series.bullets.push(function () {
        return Am5.Bullet.new(root, {
            sprite: Am5.Label.new(root, {
                fill: Am5.color(0xffffff),
                populateText: true,
                centerX: Am5.p50,
                centerY: Am5.p50,
                fontSize: 10,
                text: "{value}",
            }),
        });
    });

    Echo.channel("items").listen("UpdateChart", (e) => {
        datos = e.items;
        series.data.setAll(0);
        datos.forEach((element) => {
            series.data.push({
                id: element.id,
                y: element.y,
                x: element.x,
                value: element.value,
            });
        });
    });

    datos.forEach((element) => {
        series.data.push({
            id: element.id,
            y: element.y,
            x: element.x,
            value: element.value,
        });
    });

    //Definimos los ejes
    yAxis.data.setAll([
        { category: "Critical" },
        { category: "Bad" },
        { category: "Medium" },
        { category: "Good" },
        { category: "Very good" },
    ]);

    xAxis.data.setAll([
        { category: "Critical" },
        { category: "Bad" },
        { category: "Medium" },
        { category: "Good" },
        { category: "Very good" },
    ]);

    //Funciones
    circleTemplate.events.on("pointerdown", (e) => {
        form.ValorA = e.target.dataItem.dataContext.id;
    });

    seriesTemplate.events.on("pointerover", (e) => {
        contenedor.value = e.target;
        form.ValorB = e.target.dataItem.dataContext.id;
    });

    circleTemplate.events.on("pointerup", (e) => {
        submit();
        let circle = e.target.dataItem.bullets[0];
        circle.animate({
            key: "targetY",
            to: 0.6,
            duration: 600,
            easing: Am5.ease.out(Am5.ease.cubic),
        });
    });

    chart.appear(1000, 10);
});

const submit = () => {
    form.transform((data) => ({
        ...data,
    })).post(route("am5.grid"));
};
</script>
<template>
    <div class="w-full h-full" id="chartdiv"></div>
</template>
