<?php

namespace BackgroundModule;

class TasksPresenter extends \Nette\Application\UI\Presenter {

	public function renderWatch( $folder, $sleep = 500000 ) {
		echo "Tasks watcher is watching folder: $folder\n";

		while( TRUE ) {
			usleep( $sleep );

			$importedExperiments = \Nette\Utils\Finder::findDirectories( '*' )
				->in( $folder )
				->imported( TRUE )
				->toArray();

			if( count( $importedExperiments ) == 0 ) {
				continue;
			}

			$unimportedTasks = \Nette\Utils\Finder::findDirectories( '*' )
				->in( $importedExperiments )
				->imported( FALSE );

			foreach( $unimportedTasks as $task ) {
				$taskFolder = new \Folder( $task );
				$taskFolder->lock();

				echo "New task called ". $taskFolder->getName() ." was found in experiment " . $taskFolder->getParent()->getName() . "\n";
			}
		}

		$this->terminate();
	}

}

