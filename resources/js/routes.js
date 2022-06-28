
import ListTimeKeeping from "./Admin/Timekeeping/ListTimeKeeping";
import Report from "./Admin/Timekeeping/Report";
import Wage from "./Admin/Timekeeping/Wage";
import Bonus from "./Admin/Timekeeping/Bonus";
import ListRequest from "./Admin/Request/ListRequest";
import Index from "./Admin/projects/Index";
import MyWork from "./Admin/projects/MyWork";

export const routes = [
    {
        path: "/time-keeping",
        name: "ListTimeKeeping",
        component: ListTimeKeeping
    },
    {
        path: "/time-keeping-report",
        name: "Report",
        component: Report
    },
    {
        path: "/time-keeping-wage",
        name: "Wage",
        component: Wage
    },
    {
        path: "/time-keeping-bonus",
        name: "Bonus",
        component: Bonus
    },
    {
        path: "/request",
        name: "ListRequest",
        component: ListRequest
    },
    {
        path: "/projects",
        name: "Index",
        component: Index
    },
    {
        path: "/my_work",
        name: "MyWork",
        component: MyWork
    }
];
