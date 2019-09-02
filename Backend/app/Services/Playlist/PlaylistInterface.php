<?php namespace App\Services\Jobs;

interface PlaylistInterface
{
	public function addTrack($userId, $term = null, $paginate = false);

	public function deleteTrack($userId, $term = null, $paginate = false);
}