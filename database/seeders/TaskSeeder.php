<?php

namespace Database\Seeders;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $project = Project::where('slug', 'e-commerce-revamp')->first();
        if (! $project) {
            return;
        }

        $pm = User::where('email', 'budi@devflow.test')->first();
        $dev1 = User::where('email', 'andi@devflow.test')->first();
        $dev2 = User::where('email', 'sari@devflow.test')->first();
        $qa = User::where('email', 'citra@devflow.test')->first();

        $labels = $project->labels()->pluck('id', 'name');

        $tasks = [
            ['title' => 'Setup project structure dan konfigurasi awal', 'status' => TaskStatus::Done, 'priority' => TaskPriority::High, 'assignee' => $dev1, 'labels' => ['Backend']],
            ['title' => 'Desain database schema untuk produk', 'status' => TaskStatus::Done, 'priority' => TaskPriority::High, 'assignee' => $dev1, 'labels' => ['Backend']],
            ['title' => 'Buat API endpoint untuk product catalog', 'status' => TaskStatus::Review, 'priority' => TaskPriority::High, 'assignee' => $dev1, 'labels' => ['Backend', 'Feature']],
            ['title' => 'Implementasi halaman product list', 'status' => TaskStatus::InProgress, 'priority' => TaskPriority::Medium, 'assignee' => $dev2, 'labels' => ['Frontend']],
            ['title' => 'Buat komponen product card yang reusable', 'status' => TaskStatus::InProgress, 'priority' => TaskPriority::Medium, 'assignee' => $dev2, 'labels' => ['Frontend']],
            ['title' => 'Integrasi payment gateway Midtrans', 'status' => TaskStatus::Backlog, 'priority' => TaskPriority::Urgent, 'assignee' => $dev1, 'labels' => ['Backend', 'Feature']],
            ['title' => 'Implementasi keranjang belanja', 'status' => TaskStatus::Backlog, 'priority' => TaskPriority::High, 'assignee' => null, 'labels' => ['Backend', 'Frontend']],
            ['title' => 'Halaman checkout & konfirmasi order', 'status' => TaskStatus::Backlog, 'priority' => TaskPriority::High, 'assignee' => null, 'labels' => ['Frontend', 'Feature']],
            ['title' => 'Testing alur pembelian end-to-end', 'status' => TaskStatus::Backlog, 'priority' => TaskPriority::Medium, 'assignee' => $qa, 'labels' => ['QA']],
            ['title' => 'Fix bug: harga tidak tampil saat produk habis', 'status' => TaskStatus::Testing, 'priority' => TaskPriority::High, 'assignee' => $qa, 'labels' => ['Bug']],
            ['title' => 'Optimasi query produk N+1 problem', 'status' => TaskStatus::Review, 'priority' => TaskPriority::Medium, 'assignee' => $dev1, 'labels' => ['Backend', 'Bug']],
            ['title' => 'Tambah fitur wishlist produk', 'status' => TaskStatus::Backlog, 'priority' => TaskPriority::Low, 'assignee' => null, 'labels' => ['Feature', 'Frontend']],
            ['title' => 'Implementasi notifikasi email order', 'status' => TaskStatus::Backlog, 'priority' => TaskPriority::Medium, 'assignee' => $dev1, 'labels' => ['Backend', 'Feature']],
            ['title' => 'Halaman profil user & riwayat pesanan', 'status' => TaskStatus::Backlog, 'priority' => TaskPriority::Low, 'assignee' => $dev2, 'labels' => ['Frontend']],
            ['title' => 'Setup CI/CD pipeline GitHub Actions', 'status' => TaskStatus::Done, 'priority' => TaskPriority::High, 'assignee' => $dev1, 'labels' => ['Backend']],
            ['title' => 'Konfigurasi Redis caching untuk produk', 'status' => TaskStatus::InProgress, 'priority' => TaskPriority::Medium, 'assignee' => $dev1, 'labels' => ['Backend']],
            ['title' => 'Responsive design untuk mobile', 'status' => TaskStatus::Backlog, 'priority' => TaskPriority::Medium, 'assignee' => $dev2, 'labels' => ['Frontend']],
            ['title' => 'Testing performa dengan JMeter', 'status' => TaskStatus::Backlog, 'priority' => TaskPriority::Low, 'assignee' => $qa, 'labels' => ['QA']],
            ['title' => 'Dokumentasi API dengan Swagger', 'status' => TaskStatus::Backlog, 'priority' => TaskPriority::Low, 'assignee' => null, 'labels' => ['Backend']],
            ['title' => 'Deploy ke production server', 'status' => TaskStatus::Backlog, 'priority' => TaskPriority::Urgent, 'assignee' => $pm, 'labels' => ['Backend']],
        ];

        foreach ($tasks as $index => $data) {
            $taskNumber = $project->nextTaskNumber();

            $task = Task::create([
                'project_id' => $project->id,
                'created_by' => $pm->id,
                'assignee_id' => $data['assignee']?->id,
                'title' => $data['title'],
                'status' => $data['status'],
                'priority' => $data['priority'],
                'task_number' => $taskNumber,
                'order' => $index,
                'completed_at' => $data['status'] === TaskStatus::Done ? now() : null,
            ]);

            $labelIds = collect($data['labels'])->map(fn ($name) => $labels[$name] ?? null)->filter()->values()->toArray();
            if ($labelIds) {
                $task->labels()->sync($labelIds);
            }
        }
    }
}
