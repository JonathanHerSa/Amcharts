<script setup>
import { onMounted, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import * as Am5 from "@amcharts/amcharts5";
import * as Am5percent from "@amcharts/amcharts5/percent";
import Am5themes_Animated from "@amcharts/amcharts5/themes/Animated";
import * as Am5plugins_exporting from "@amcharts/amcharts5/plugins/exporting";

const props = defineProps(["datos"]);
const form = useForm({
    id: "",
    maker: "",
});

onMounted(() => {
    let root = Am5.Root.new("chartdiv");

    root.setThemes([Am5themes_Animated.new(root)]);

    let chart = root.container.children.push(
        Am5percent.PieChart.new(root, {
            endAngle: 270,
        })
    );

    let series = chart.series.push(
        Am5percent.PieSeries.new(root, {
            valueField: "value",
            categoryField: "category",
            endAngle: 270,
        })
    );

    series.states.create("hidden", {
        endAngle: -90,
    });

    series.labels.template.set("forceHidden", true);
    series.ticks.template.set("forceHidden", true);

    series.data.setAll([
        {
            category: "Lithuania",
            value: 501.9,
        },
        {
            category: "Czechia",
            value: 301.9,
        },
        {
            category: "Ireland",
            value: 201.1,
        },
        {
            category: "Germany",
            value: 165.8,
        },
        {
            category: "Australia",
            value: 139.9,
        },
        {
            category: "Austria",
            value: 128.3,
        },
        {
            category: "UK",
            value: 99,
        },
    ]);

    series.appear(1000, 100);

    // Set up export and annotation
    let exporting = Am5plugins_exporting.Exporting.new(root, {
        menu: Am5plugins_exporting.ExportingMenu.new(root, {}),
    });

    let annotationData = {
        id: "",
        markers: [],
    };

    let annotator = Am5plugins_exporting.Annotator.new(root, {});
    props.datos.forEach((element) => {
        annotationData.id = element.id;
        annotationData.markers = JSON.parse(element.maker);
    });

    annotator.set("markerState", annotationData);

    let menuitems = exporting.get("menu").get("items");

    menuitems.push({
        type: "separator",
    });

    menuitems.push({
        type: "custom",
        label: "Annotate",
        callback: function () {
            this.close();
            annotator.toggle();
        },
    });
    const cambios = document.getElementById("save");
    cambios.addEventListener("click", (e) => {
        let a = annotator.get("markerState");
        form.id = a.id;
        form.maker = JSON.stringify(a.markers);
        submit();
    });
});
const submit = () => {
    form.transform((data) => ({
        ...data,
    })).post(route("am5.notes"));
};
</script>
<template>
    <div class="w-full h-full" id="chartdiv"></div>
    <button class="bg-cyan-300 rounded-3xl w-fit px-3 py-1" id="save">
        Guardar Cambios
    </button>
</template>
