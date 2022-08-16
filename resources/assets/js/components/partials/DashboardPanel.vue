<template>
    <div :class="'panel ' + panelStyles.join(' ')">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-3">
                    <i :class="panelIconStyles.join(' ')"></i>
                </div>
                <div class="col-xs-9 text-right">
                    <div class="head-text">{{panelHeaderText}}</div>
                </div>
            </div>
        </div>
        <a v-for="item in panelItems" :href="item.link">
            <div class="panel-footer">
                <span class="pull-left"><i :class="item.iconStyles.join(' ')" aria-hidden="true"></i> {{item.linkText}}</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
            </div>
        </a>
    </div>
</template>
<script>
    export default {
        /**
        * panelStyles: Array
        * panelHeaderText: String
        * panelItems: [{ link: String, iconStyles: Array, linkText: String }]
        */
        props: {
            panelStyles: Array,
            panelIconStyles: Array,
            panelHeaderText: String,
            panelItems: Array,
        }
    }
</script>
<style scoped>
    .head-text {
        font-size: 25px;
    }
</style>