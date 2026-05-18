---
name: recap
description: Post-review step — explain what was just built in a fixed three-section format. Use after AI review and before /opsx:sync to ensure the developer understands the feature. Always produces a Mermaid diagram.
compatibility: Requires an active OpenSpec change with implementation complete.
---

Generate a structured recap of the feature that was just implemented. This runs **after AI review** and **before `/opsx:sync`**.

The goal is to ensure the developer fully understands what was built — not just that it works.

**Input**: Optionally specify a change name after `/recap` (e.g. `/recap add-auth`). If omitted, infer from context. If ambiguous, use `openspec list --json` and ask the user to select.

**Steps**

1. **Read the implementation**

   Read the relevant code files. Cross-reference with `proposal.md`, `design.md`, and `tasks.md` in `openspec/changes/<name>/` to understand what was intended.

2. **Generate the recap in exactly three sections**

   Produce the recap as a markdown response. Do NOT skip any section:

   ---

   ## How does this work?

   Plain-language explanation of the feature — what it does and why. No code. Write it so a developer who wasn't part of the implementation would understand the feature in 3-4 sentences.

   ---

   ## What is the flow?

   Step-by-step description of the user/data flow. Use numbered steps. Be specific: which component handles what, what is passed where, what happens at each step.

   ---

   ## Diagram

   A Mermaid diagram visualizing the flow. This section is **always required** — no exceptions.

   Use `sequenceDiagram` for request/response flows, `flowchart TD` for decision flows, or `graph LR` for data flows. Choose the type that best represents this feature.

   ```mermaid
   sequenceDiagram
       participant User
       participant System
       User->>System: ...
   ```

   ---

3. **Validate the recap**

   Confirm that:
   - All three sections are present
   - The Mermaid diagram block is syntactically correct
   - The explanation matches what is actually implemented (not just what was planned)

   If the diagram is missing or syntactically broken, regenerate it before responding.

4. **Bridge to next step**

   After the recap, add:
   > "Review the explanation and diagram. If something looks wrong or unclear, we can fix it now. When ready, run `/opsx:sync` to merge specs, then `/opsx:archive` to close the change."

**Guardrails**
- Always read actual code — do not rely only on `tasks.md` or `design.md`
- The Mermaid diagram is mandatory, even for small changes
- Keep each section concise — the recap is not a technical deep-dive, it is a comprehension check
- Do not suggest any code changes during the recap
