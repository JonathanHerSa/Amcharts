<script setup>
import { onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import * as Am5 from "@amcharts/amcharts5";
import * as Am5XY from "@amcharts/amcharts5/xy";
import Am5themes_Animated from "@amcharts/amcharts5/themes/Animated";

const props = defineProps(["datos"]);

const form = useForm({
    ValorA: "",
    ValorB: "",
});

onMounted(() => {
    //instanciamos el metodo raiz
    const rootG = Am5.Root.new("chartdiv");

    rootG.setThemes(Am5themes_Animated.new([rootG]));

    //instanciamos el grafico deseado
    const chart = rootG.container.children.push(
        Am5XY.XYChart.new(rootG, {
            layout: rootG.verticalLayout,
        })
    );

    //instanciamos los valores
    let yAxis = chart.yAxes.push(
        Am5XY.ValueAxis.new(rootG, {
            renderer: Am5XY.AxisRendererY.new(rootG, {}),
        })
    );

    let xAxis = chart.xAxes.push(
        Am5XY.CategoryAxis.new(rootG, {
            renderer: Am5XY.AxisRendererX.new(rootG, {}),
            categoryField: "category",
        })
    );

    xAxis.data.setAll(props.datos);

    const series = chart.series.push(
        Am5XY.ColumnSeries.new(rootG, {
            name: "series",
            xAxis: xAxis,
            yAxis: yAxis,
            categoryXField: "category",
            valueYField: "value",
        })
    );
    let seriestemplate = series.columns.template;
    seriestemplate.setAll({
        tooltipText: "{value}",
        draggable: true,
    });

    let legend = chart.children.push(Am5.Legend.new(rootG, {}));
    legend.data.setAll(chart.series.values);

    let previousValue;
    //pasamos la informacion
    props.datos.forEach((element) => {
        series.data.push({
            id: element.id,
            category: element.category,
            value: element.value,
        });
    });

    seriestemplate.events.on("pointerdown", (e) => {
        form.ValorA = e.target.dataItem.dataContext;
    });
    seriestemplate.events.on("pointerover", (e) => {
        form.ValorB = e.target.dataItem.dataContext;
    });
    seriestemplate.events.on("pointerup", (e) => {
        submit();
    });
});

const submit = () => {
    form.transform((data) => ({
        ...data,
    })).post(route("am5.store"));
};
</script>
<template>
    <div class="h-full w-full" id="chartdiv"></div>
</template>
