<?php

	declare(strict_types=1);

	namespace OCA\Library\Migration;

	use Closure;
	use OCP\DB\ISchemaWrapper;
	use OCP\DB\Types;
	use OCP\Migration\IOutput;
	use OCP\Migration\SimpleMigrationStep;

	class Version010000Date20240211153814 extends SimpleMigrationStep {

		/**
		 * @param IOutput $output
		 * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
		 * @param array $options
		 */
		public function preSchemaChange(IOutput $output, Closure $schemaClosure, array $options)
		{
		}

		/**
		 * @param IOutput $output
		 * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
		 * @param array $options
		 * @return null|ISchemaWrapper
		 */
		public function changeSchema(IOutput $output, Closure $schemaClosure, array $options)
		{
			/** @var ISchemaWrapper $schema */
			$schema = $schemaClosure();

			if (!$schema->hasTable('library_notes')) {
				$table = $schema->createTable('library_notes');
				$table->addColumn('id', Types::BIGINT, [
					'autoincrement' => true,
					'notnull' => true,
					'length' => 4,
				]);
				$table->addColumn('user_id', Types::STRING, [
					'notnull' => true,
					'length' => 64,
				]);
				$table->addColumn('name', Types::STRING, [
					'notnull' => true,
					'length' => 300,
				]);
				$table->addColumn('content', Types::TEXT, [
					'notnull' => true,
				]);
				$table->addColumn('last_modified', Types::INTEGER, [
					'notnull' => true,
				]);
				$table->setPrimaryKey(['id']);
				$table->addIndex(['user_id'], 'library_notes_uid');
			}

			return $schema;
		}

		/**
		 * @param IOutput $output
		 * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
		 * @param array $options
		 */
		public function postSchemaChange(IOutput $output, Closure $schemaClosure, array $options)
		{
		}
	}