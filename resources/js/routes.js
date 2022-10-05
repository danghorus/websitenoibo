
import ListTimeKeeping from "./Admin/Timekeeping/ListTimeKeeping";
import Report from "./Admin/Timekeeping/Report";
import Wage from "./Admin/Timekeeping/Wage";
import Bonus from "./Admin/Timekeeping/Bonus";
import ListRequest from "./Admin/Request/ListRequest";
import Index from "./Admin/projects/Index";
import MyWork from "./Admin/projects/MyWork";
import ListWork from "./Admin/projects/ListWork";
import Project_Report from "./Admin/projects/Report";
import Project_Warrior from "./Admin/projects/Warrior";
import InvalidTasks from "./Admin/projects/InvalidTasks";
import ListProposal from "./Admin/Proposal/ListProposal";

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
    },
    {
        path: "/list_work",
        name: "ListWork",
        component: ListWork
    },
    {
        path: "/project-report",
        name: "Project_Report",
        component: Project_Report
    },
    {
        path: "/warrior",
        name: "Project_Warrior",
        component: Project_Warrior
    },
    {
        path: "/invalid_tasks",
        name: "Invalid Tasks",
        component: InvalidTasks
    },
    {
        path: "/proposals",
        name: "List Proposal",
        component: ListProposal
    }
];
