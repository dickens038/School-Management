<form action="{{ route('settings.backup') }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-warning">Create Database Backup</button>
</form>
<p class="mt-3 text-muted">Backups are stored in the storage/backups directory. Download via server or ask IT admin.</p> 