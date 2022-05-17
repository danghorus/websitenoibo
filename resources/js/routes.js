
import ListTimeKeeping from "./Admin/Timekeeping/ListTimeKeeping";
import Report from "./Admin/Timekeeping/Report";
import ListRequest from "./Admin/Request/ListRequest";

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
        path: "/request",
        name: "ListRequest",
        component: ListRequest
    }
];
