#Business Rules
##Process Sequence Rules

Jobs contain processes, and that set of processes have names, prerequisites, and startDates.

The prerequisite field contains the id of another process or null. The prerequisite (if filled) determines the process that the current process must follow. If a process has a prerequisite, it's start date must equal or be greater than its prerequisite.

Imagine a job with a set of 5 processes.

| id  | prerequisite | name | startDate |
| --- | ------------ | ---- | --------- |
| 0   | null         | foo  | 2/1/2023  |
| 1   | 0            | bar  | 2/2/2023  |
| 2   | 1            | maa  | 2/3/2023  |
| 3   | 2            | waa  | 2/4/2023  |
| 4   | 3            | do   | 2/5/2023  |

| id  | prerequisite | name | startDate |
| --- | ------------ | ---- | --------- |
| 0   | null         | foo  | 2/1/2023  |
| 1   | 0            | bar  | 2/2/2023  |
| 2   | null         | maa  | 2/1/2023  |
| 3   | 2            | waa  | 2/2/2023  |
| 4   | 3            | do   | 2/3/2023  |

| id  | prerequisite | name | startDate |
| --- | ------------ | ---- | --------- |
| 0   | null         | foo  | 2/1/2023  |
| 1   | 0            | bar  | 2/2/2023  |
| 2   | 1            | maa  | 2/3/2023  |
| 3   | 2            | waa  | 2/4/2023  |
| 4   | 3            | do   | 2/5/2023  |
