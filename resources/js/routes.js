import EmployeesIndex from "./components/employees/Index";
import EmployeesCreate from "./components/employees/Create";
import EmployeesEdit from "./components/employees/Edit";
import ListTimeKeeping from "./Admin/Timekeeping/ListTimeKeeping";
import Report from "./Admin/Timekeeping/Report";

export const routes = [
    {
        path: "/employees",
        name: "EmployeesIndex",
        component: EmployeesIndex
    },
    {
        path: "/employees/create",
        name: "EmployeesCreate",
        component: EmployeesCreate
    },
    {
        path: "/employees/:id",
        name: "EmployeesEdit",
        component: EmployeesEdit
    },
    {
        path: "/time-keeping",
        name: "ListTimeKeeping",
        component: ListTimeKeeping
    },
    {
        path: "/time-keeping-report",
        name: "Report",
        component: Report
    }
];
